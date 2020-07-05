import '../setup';
import { render, fireEvent, waitForElementToBeRemoved } from '@testing-library/vue';
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
      template: template || '<div />',
      props: ['resources']
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
    it('loads resources when in edit mode', async function () {
      moxios.install();

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

      moxios.uninstall();
    });
  });
});
