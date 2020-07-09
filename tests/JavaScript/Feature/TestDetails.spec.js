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

const renderDetails = props => render(Details, {
  props: {
    title: '',
    value: {},
    pages: [pageFactory('foo')],
    ...props
  }
});

describe('Details.vue', function () {
  afterEach(function () {
    delete window.location;
    window.location = realLocation;
    window.location.hash = '';
  });

  describe('viewing details', function () {
    it('shows its title', function () {
      const { queryByText } = renderDetails({ title: 'Test title' });
      expect(queryByText('Test title')).to.exist;
    });

    it('shows its page names', function () {
      const { queryByText } = renderDetails({
        pages: [pageFactory('foo-bar'), pageFactory('baz')]
      });

      expect(queryByText('foo bar')).to.exist;
      expect(queryByText('baz')).to.exist;
    });
  });

  describe('navigation', function () {
    it('marks the first page as active by default', function () {
      const { getByText } = renderDetails({
        pages: [pageFactory('foo-bar'), pageFactory('baz')]
      });

      expect(getByText('foo bar')).to.have.class('active-nav');
      expect(getByText('baz')).to.have.class('inactive-nav');
    });

    it('visits the page marked by the location hash', function () {
      visitUri('/foo#baz');

      const { queryByText } = renderDetails({
        pages: [
          pageFactory('foo-bar', '<p>Foo bar</p>'),
          pageFactory('baz', '<p>Baz</p>')
        ]
      });

      expect(queryByText('Baz')).to.exist;
      expect(queryByText('Foo bar')).to.not.exist;
    });

    it('responds to the url hash changing from a click', async function () {
      const { getByText, queryByText } = renderDetails({
        pages: [
          pageFactory('foo-bar', '<p>Page 1</p>'),
          pageFactory('baz', '<p>Page 2</p>')
        ]
      });
      expect(queryByText('Page 1')).to.exist;
      expect(queryByText('Page 2')).to.not.exist;

      await fireEvent.click(getByText('baz'));
      await fireEvent(window, new HashChangeEvent('hashchange')); // testing-library suppresses this event

      expect(queryByText('Page 2')).to.exist;
      expect(queryByText('Page 1')).to.not.exist;
    });

    it('response to the url hash changing programmatically', async function () {
      visitUri('/foo#bar');

      const { queryByText } = renderDetails({
        pages: [
          pageFactory('foo-bar', '<p>Page 1</p>'),
          pageFactory('baz', '<p>Page 2</p>')
        ]
      });

      expect(queryByText('Page 1')).to.exist;
      expect(queryByText('Page 2')).to.not.exist;
    });

    it('defaults to the first page if the hash cannot be matched', function () {
      const { getByText } = renderDetails({
        pages: [pageFactory('foo-bar'), pageFactory('baz')]
      });

      expect(getByText('foo bar')).to.have.class('active-nav');
      expect(getByText('baz')).to.have.class('inactive-nav');
    });

    it('activates a page when clicked', async function () {
      const { queryByText, getByText } = renderDetails({
        pages: [
          pageFactory('foo-bar', '<div>Foo Bar</div>'),
          pageFactory('baz', '<div>Baz</div>')
        ]
      });
      expect(queryByText('Foo Bar')).to.exist;
      expect(queryByText('Baz')).to.not.exist;

      await fireEvent.click(getByText('baz'));
      await fireEvent(window, new HashChangeEvent('hashchange')); // testing-library suppresses this event

      expect(window.location.hash).to.equal('#baz');
      expect(getByText('foo bar')).to.have.class('inactive-nav');
      expect(getByText('baz')).to.have.class('active-nav');
      expect(queryByText('Foo Bar')).to.not.exist;
      expect(queryByText('Baz')).to.exist;
    });
  });

  describe('editing details', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

    it('shows an edit button if the user can edit', function () {
      const { queryByLabelText } = renderDetails({ canEdit: true });
      expect(queryByLabelText('Edit')).to.exist;
    });

    it('does not show an edit button if the user cannot edit', function () {
      const { queryByLabelText } = renderDetails({ canEdit: false, mode: 'view' });
      expect(queryByLabelText('Edit')).to.not.exist;
    });

    it('emits a modeChange event if the edit button is pressed', async function () {
      const { getByLabelText, emitted } = renderDetails({ canEdit: true, mode: 'view' });

      await fireEvent.click(getByLabelText('Edit'));

      expect(emitted().modeChange).to.exist;
      expect(emitted().modeChange[0][0]).to.equal('edit');
    });

    it('shows a save button when in edit mode', function () {
      const { queryByLabelText } = renderDetails({ canEdit: true, mode: 'edit' });
      expect(queryByLabelText('Save')).to.exist;
      expect(queryByLabelText('Edit')).to.not.exist;
    });

    it('loads resources when in edit mode', async function () {
      moxios.stubRequest('/items', {
        response: { data: [{ name: 'Foo' }] }
      });

      const { getByText, queryByText } = renderDetails({
        mode: 'edit',
        pages: [
          pageFactory('foo-bar', '<ul><li v-for="item of resources.items" :key="item.name">{{ item.name }}</li></ul>')
        ],
        resources: [{ key: 'items', url: '/items' }]
      });

      await waitForElementToBeRemoved(getByText('Loading...'));

      expect(queryByText('Foo')).to.exist;
    });

    it('loads resources when switched to edit mode', async function () {
      moxios.stubRequest('/items', {
        response: { data: [{ name: 'Foo' }] }
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
