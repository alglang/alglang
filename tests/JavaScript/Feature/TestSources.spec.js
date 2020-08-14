import '../setup';
import { fireEvent, render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Sources from '../../../resources/js/components/Sources';
import { sourceFactory } from '../factory';

const renderSources = props => {
  const wrapper = render(Sources, {
    props: {
      url: '/api/sources',
      ...props
    }
  });

  const waitForLoad = async () => waitForElementToBeRemoved(wrapper.getByLabelText('Loading'));

  const filterByText = async text => {
    await fireEvent.input(wrapper.getByLabelText('Filter'), {
      target: { value: text }
    });
  };

  const filterByTextAndWait = async text => {
    await filterByText(text);
    await waitForLoad();
  };

  const clickNext = async () => fireEvent.click(wrapper.getByLabelText('Next'));
  const clickPrev = async () => fireEvent.click(wrapper.getByLabelText('Previous'));

  const clickNextAndWait = async () => {
    await clickNext();
    await waitForLoad();
  };

  return {
    clickNext,
    clickNextAndWait,
    clickPrev,
    filterByText,
    filterByTextAndWait,
    waitForLoad,
    ...wrapper
  };
};

const renderSourcesAndWait = async props => {
  const wrapper = renderSources(props);
  await wrapper.waitForLoad();
  return wrapper;
};

describe('Sources.vue', function () {
  beforeEach(function () {
    moxios.install();

    moxios.stubRequest('/api/sources', {
      response: {
        data: [
          sourceFactory({ short_citation: 'Jane Doe 2000a' }),
          sourceFactory({ short_citation: 'John Doe 1234b' }),
          sourceFactory({ short_citation: 'Foo Bar 5432' })
        ],
        links: {
          next: '/api/sources-next'
        }
      }
    });

    moxios.stubRequest('/api/sources-next', {
      response: {
        data: [
          sourceFactory({ short_citation: 'Jane Doe 2000b' })
        ],
        links: {}
      }
    });
  });

  afterEach(function () { moxios.uninstall(); });

  it('shows that it is loading when it first mounts', function () {
    const { queryByLabelText } = renderSources();
    expect(queryByLabelText('Loading')).to.exist;
  });

  it('loads sources', async function () {
    const { queryByText } = await renderSourcesAndWait();

    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
    expect(queryByText('Foo Bar 5432')).to.exist;
  });

  it('restricts the number of sources', async function () {
    const { queryByText } = await renderSourcesAndWait({ perPage: 2 });

    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
    expect(queryByText('Foo Bar 5432')).to.not.exist;
  });

  it('filters sources', async function () {
    const { filterByText, queryByText } = await renderSourcesAndWait();

    await filterByText('John');

    expect(queryByText('Jane Doe 2000a')).to.not.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
    expect(queryByText('Foo Bar 5432')).to.not.exist;
  });

  it('loads the next page if it runs out of sources', async function () {
    const { filterByTextAndWait, queryByText } = await renderSourcesAndWait({ perPage: 2 });

    expect(queryByText('Jane Doe 2000b')).to.not.exist;

    await filterByTextAndWait('Jane');

    expect(queryByText('Jane Doe 2000b')).to.exist;
  });

  it('navigates to the next page', async function () {
    const { clickNext, queryByText } = await renderSourcesAndWait({ perPage: 1 });
    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('John Doe 1234b')).to.not.exist;

    await clickNext();

    expect(queryByText('Jane Doe 2000a')).to.not.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
  });

  it('does not navigate to the next page if there are no more sources', async function () {
    const { clickNext, queryByText } = await renderSourcesAndWait({ url: '/api/sources-next' });
    expect(queryByText('Jane Doe 2000b')).to.exist;

    await clickNext();

    expect(queryByText('Jane Doe 2000b')).to.exist;
  });

  it('navigates to the next page if there are more loadable sources', async function () {
    const { clickNextAndWait, queryByText } = await renderSourcesAndWait({ perPage: 3 });
    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('Jane Doe 2000b')).to.not.exist;

    await clickNextAndWait();

    expect(queryByText('Jane Doe 2000a')).to.not.exist;
    expect(queryByText('Jane Doe 2000b')).to.exist;
  });

  it('does not navigate to the next page if sources are loading', async function () {
    const { clickNext, queryByText, waitForLoad } = await renderSourcesAndWait({ perPage: 3 });

    clickNext();
    await clickNext();
    await waitForLoad();

    expect(queryByText('Jane Doe 2000b')).to.exist;
  });

  it('navigates to the previous page', async function () {
    const { clickNext, clickPrev, queryByText } = await renderSourcesAndWait({ perPage: 1 });
    expect(queryByText('Jane Doe 2000a')).to.exist;

    await clickNext();

    expect(queryByText('Jane Doe 2000a')).to.not.exist;

    await clickPrev();

    expect(queryByText('Jane Doe 2000a')).to.exist;
  });

  it('does not navigate to the previous page if it is on the first page', async function () {
    const { clickPrev, queryByText } = await renderSourcesAndWait({ perPage: 1 });
    expect(queryByText('Jane Doe 2000a')).to.exist;

    await clickPrev();

    expect(queryByText('Jane Doe 2000a')).to.exist;
  });
});
