import '../setup';
import { render, fireEvent } from '@testing-library/vue';
import { expect } from 'chai';
import Vue from 'vue';

import Details from '../../../resources/js/components/Details';
import DetailPage from '../../../resources/js/components/DetailPage';

const renderDetails = ({ props, slots }) => render(Details, {
  props: {
    title: 'Dummy title',
    ...props
  },
  slots: {
    ...slots
  },
  stubs: {
    'detail-page': DetailPage
  }
});

const renderDetailsAndWait = async params => {
  const wrapper = renderDetails(params);
  await Vue.nextTick();
  return wrapper;
};

const hashChange = async hash => {
  window.location.hash = hash;
  await fireEvent(window, new HashChangeEvent('hashchange'));
};

describe('Details.vue', function () {
  beforeEach(function () {
    this.oldLocation = window.location;
    delete window.location;
    window.location = new URL('http://localhost');
  });

  afterEach(function () {
    delete window.location;
    window.location = this.oldLocation;
  });

  it('shows its title', function () {
    const props = { title: 'Test title' };
    const { getByText } = renderDetails({ props });

    expect(getByText('Test title')).to.have.tagName('h2');
  });

  it('renders into the header slot', function () {
    const slots = {
      header: '<h1 data-testid="header">Foo</h1>'
    };

    const { getByTestId } = renderDetails({ slots });

    expect(getByTestId('header')).to.have.trimmed.text('Foo');
  });

  it('displays the page titles', async function () {
    const slots = {
      default: [
        '<detail-page title="Foo"></detail-page>',
        '<detail-page title="Bar"></detail-page>'
      ]
    };

    const { getAllByRole } = await renderDetailsAndWait({ slots });

    const tabs = getAllByRole('tab');
    expect(tabs[0]).to.contain.trimmed.text('Foo');
    expect(tabs[1]).to.contain.trimmed.text('Bar');
  });

  it('renders the first page in its default slot', async function () {
    const slots = {
      default: '<detail-page title=""><div data-testid="page1-content" /></detail-page>'
    };

    const { queryByTestId } = await renderDetailsAndWait({ slots });

    expect(queryByTestId('page1-content')).to.exist;
  });

  it('only renders one page at a time', async function () {
    const slots = {
      default: [
        '<detail-page title="a"><div data-testid="page1-content" /></detail-page>',
        '<detail-page title="b"><div data-testid="page2-content" /></detail-page>'
      ]
    };

    const { getAllByRole, queryByTestId } = await renderDetailsAndWait({ slots });

    const tabs = getAllByRole('tab');

    expect(queryByTestId('page1-content')).to.exist;
    expect(queryByTestId('page2-content')).to.not.exist;
    expect(tabs[0]).to.have.attribute('aria-selected');
    expect(tabs[1]).to.not.have.attribute('aria-selected');
  });

  it('navigates to page when its title is clicked', async function () {
    const slots = {
      default: [
        '<detail-page title="a"><div data-testid="page1-content" /></detail-page>',
        '<detail-page title="b"><div data-testid="page2-content" /></detail-page>'
      ]
    };

    const { getAllByRole, queryByTestId } = await renderDetailsAndWait({ slots });

    const tabs = getAllByRole('tab');

    await fireEvent.click(tabs[1]);

    expect(queryByTestId('page1-content')).to.not.exist;
    expect(queryByTestId('page2-content')).to.exist;
    expect(tabs[0]).to.not.have.attribute('aria-selected');
    expect(tabs[1]).to.have.attribute('aria-selected');
    expect(window.location.hash).to.equal('#b');
  });

  it('navigates to page when the hash changes', async function () {
    const slots = {
      default: [
        '<detail-page title="a"><div data-testid="page1-content" /></detail-page>',
        '<detail-page title="b"><div data-testid="page2-content" /></detail-page>'
      ]
    };

    const { getAllByRole, queryByTestId } = await renderDetailsAndWait({ slots });

    const tabs = getAllByRole('tab');

    await fireEvent.click(tabs[1]);
    await hashChange('#a');

    expect(queryByTestId('page1-content')).to.exist;
    expect(queryByTestId('page2-content')).to.not.exist;
    expect(tabs[0]).to.have.attribute('aria-selected');
    expect(tabs[1]).to.not.have.attribute('aria-selected');
  });

  it('starts on a page if its hash matches the title', async function () {
    const slots = {
      default: [
        '<detail-page title="Baz"><div data-testid="page1-content" /></detail-page>',
        '<detail-page title="Foo bar"><div data-testid="page2-content" /></detail-page>'
      ]
    };

    window.location.hash = '#foo-bar';
    const { getAllByRole, queryByTestId } = await renderDetailsAndWait({ slots });

    const tabs = getAllByRole('tab');

    expect(queryByTestId('page1-content')).to.not.exist;
    expect(queryByTestId('page2-content')).to.exist;
    expect(tabs[0]).to.not.have.attribute('aria-selected');
    expect(tabs[1]).to.have.attribute('aria-selected');
  });

  it('renders the first page if the hash does not match a page', async function () {
    const slots = {
      default: [
        '<detail-page title="Baz"><div data-testid="page1-content" /></detail-page>',
        '<detail-page title="Foo bar"><div data-testid="page2-content" /></detail-page>'
      ]
    };

    window.location.hash = '#fizz-buzz';
    const { getAllByRole, queryByTestId } = await renderDetailsAndWait({ slots });

    const tabs = getAllByRole('tab');

    expect(queryByTestId('page1-content')).to.exist;
    expect(queryByTestId('page2-content')).to.not.exist;
    expect(tabs[0]).to.have.attribute('aria-selected');
    expect(tabs[1]).to.not.have.attribute('aria-selected');
    expect(window.location.hash).to.equal('#baz');
  });
});
