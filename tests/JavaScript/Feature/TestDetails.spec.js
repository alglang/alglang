import '../setup';
import {
  render,
  fireEvent,
  waitFor,
  waitForElementToBeRemoved
} from '@testing-library/vue';
import Vue from 'vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Details from '../../../resources/js/components/Details';

const realLocation = window.location;

const visitUri = uri => {
  delete window.location;
  window.location = new URL(`http://localhost${uri}`);
};

const pageFactory = (name, template) => {
  const component = Vue.component(
    name,
    {
      props: {
        resources: {
          type: Object,
          default: () => ({})
        }
      },
      template: template || '<div />'
    }
  );
  return { name, component };
};

describe('Details.vue', function () {
  afterEach(function () {
    delete window.location;
    window.location = realLocation;
    window.location.hash = '';
  });

  describe('viewing details', function () {
    it('shows its title', function () {
      const props = {
        value: {},
        title: 'Test title',
        pages: [pageFactory('foo')]
      };

      const { queryByText } = render(Details, { props });

      expect(queryByText('Test title')).to.be.ok;
    });

    it('shows its page names', function () {
      const props = {
        value: {},
        title: '',
        pages: [
          pageFactory('foo-bar'),
          pageFactory('baz')
        ]
      };

      const { queryByText } = render(Details, { props });

      expect(queryByText('foo bar')).to.be.ok;
      expect(queryByText('baz')).to.be.ok;
    });
  });

  describe('navigation', function () {
    it('marks the first page as active by default', function () {
      const props = {
        value: {},
        title: '',
        pages: [
          pageFactory('foo-bar'),
          pageFactory('baz')
        ]
      };

      const { getByText } = render(Details, { props });

      const firstLink = getByText('foo bar');
      const secondLink = getByText('baz');

      expect(firstLink).to.have.class('active-nav');
      expect(secondLink).to.have.class('inactive-nav');
    });

    it('visits the page marked by the location hash', function () {
      visitUri('/foo#baz');

      const props = {
        value: {},
        title: '',
        pages: [
          pageFactory('foo-bar', '<p>Foo bar</p>'),
          pageFactory('baz', '<p>Baz</p>')
        ]
      };

      const { getByText, queryByText } = render(Details, { props });

      expect(getByText('Baz'));
      expect(queryByText('Foo bar')).to.be.null;
    });

    it('responds to the url hash changing', async function () {
      const props = {
        value: {},
        title: '',
        pages: [
          pageFactory('foo-bar', '<p>Page 1</p>'),
          pageFactory('baz', '<p>Page 2</p>')
        ]
      };

      const { getByText, queryByText } = render(Details, { props });

      await fireEvent.click(getByText('baz'));
      await fireEvent(window, new HashChangeEvent('hashchange')); // testing-library suppresses this event

      expect(window.location.hash).to.equal('#baz');
      expect(getByText('Page 2'));
      expect(queryByText('Page 1')).to.be.null;

      window.location.hash = '';
      await fireEvent(window, new HashChangeEvent('hashchange')); // testing-library suppresses this event

      expect(window.location.hash).to.equal('');
      expect(getByText('Page 1'));
      expect(queryByText('Page 2')).to.be.null;
    });

    it('activates a page when clicked', async function () {
      const props = {
        value: {},
        title: '',
        pages: [
          pageFactory('foo-bar', '<div>Foo Bar</div>'),
          pageFactory('baz', '<div>Baz</div>')
        ]
      };

      const { queryByText, getByText } = render(Details, { props });
      expect(queryByText('Foo Bar')).to.be.ok;
      expect(queryByText('Baz')).to.be.null;

      const firstLink = getByText('foo bar');
      const secondLink = getByText('baz');
      await fireEvent.click(secondLink);
      await fireEvent(window, new HashChangeEvent('hashchange')); // testing-library suppresses this event

      expect(window.location.hash).to.equal('#baz');
      expect(firstLink).to.have.class('inactive-nav');
      expect(secondLink).to.have.class('active-nav');
      expect(queryByText('Foo Bar')).to.be.null;
      expect(queryByText('Baz')).to.be.ok;
    });
  });

  describe('editing details', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

    it('shows an edit button if the user can edit', function () {
      const props = {
        canEdit: true,
        value: {},
        title: '',
        pages: [pageFactory('foo')]
      };

      const { getByLabelText } = render(Details, { props });

      expect(getByLabelText('Edit')).to.have.tagName('a');
    });

    it('emits a modeChange event if the edit button is pressed', async function () {
      const props = {
        canEdit: true,
        mode: 'view',
        value: {},
        title: '',
        pages: [pageFactory('foo')]
      };

      const { getByLabelText, emitted } = render(Details, { props });

      await fireEvent.click(getByLabelText('Edit'));

      expect(emitted().modeChange).to.exist;
      expect(emitted().modeChange[0][0]).to.equal('edit');
    });
    it('does not show an edit button if the user cannot edit', function () {
      const props = {
        canEdit: false,
        mode: 'view',
        value: {},
        title: '',
        pages: [pageFactory('foo')]
      };

      const { queryByLabelText } = render(Details, { props });

      expect(queryByLabelText('Edit')).to.not.exist;
    });

    it('shows a save button when in edit mode', function () {
      const props = {
        value: {},
        title: '',
        canEdit: true,
        mode: 'edit',
        pages: [pageFactory('foo')]
      };

      const { queryByLabelText } = render(Details, { props });

      expect(queryByLabelText('Save')).to.exist;
      expect(queryByLabelText('Edit')).to.not.exist;
    });

    it('loads resources when in edit mode', async function () {
      moxios.stubRequest('/items', {
        status: 200,
        response: {
          data: [{ name: 'Foo' }]
        }
      });

      const props = {
        value: {},
        title: '',
        mode: 'edit',
        pages: [
          pageFactory('foo-bar', '<ul><li v-for="item of resources.items" :key="item.name">{{ item.name }}</li></ul>')
        ],
        resources: [
          {
            key: 'items',
            url: '/items'
          }
        ]
      };

      const { getByText } = render(Details, { props });

      await waitForElementToBeRemoved(getByText('Loading...'));

      expect(getByText('Foo'));
    });

    it('loads resources when switched to edit mode', async function () {
      moxios.stubRequest('/items', {
        status: 200,
        response: {
          data: [{ name: 'Foo' }]
        }
      });

      const wrapper = Vue.component('wrapper', {
        components: {
          'alglang-details': Details
        },

        data() {
          return {
            mode: 'view',
            value: {},
            pages: [
              pageFactory('foo-bar', '<ul><li v-for="item of resources.items" :key="item.name">{{ item.name }}</li></ul>')
            ],
            resources: [{ key: 'items', url: '/items' }]
          };
        },

        template: `<div>
          <alglang-details
            :can-edit="true"
            :mode="mode"
            :value="value"
            :pages="pages"
            :resources="resources"
            title=""
            @modeChange="mode = $event"
          />
        </div>`
      });

      const { queryByText, getByText, getByLabelText } = render(wrapper);

      await fireEvent.click(getByLabelText('Edit'));
      await waitFor(() => expect(getByText('Loading...')));
      await waitForElementToBeRemoved(queryByText('Loading...'));
      await waitFor(() => expect(moxios.requests.count()).to.equal(1));
      expect(getByText('Foo'));
    });
  });
});
