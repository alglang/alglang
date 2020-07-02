import '../setup';
import { render, fireEvent } from '@testing-library/vue';
import Vue from 'vue';
import { expect } from 'chai';

import Details from '../../../resources/js/components/Details';

const realLocation = window.location;

const visitUri = uri => {
  delete window.location;
  window.location = new URL(`http://localhost${uri}`);
};

const pageFactory = (name, template) => {
  const component = Vue.component(name, { template: template || '<div />' });
  return { name, component };
};

describe('Details.vue', function () {
  afterEach(function () {
    delete window.location;
    window.location = realLocation;
    window.location.hash = '';
  });

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

    expect(window.location.hash).to.equal('#baz');
    expect(firstLink).to.have.class('inactive-nav');
    expect(secondLink).to.have.class('active-nav');
    expect(queryByText('Foo Bar')).to.be.null;
    expect(queryByText('Baz')).to.be.ok;
  });
});
