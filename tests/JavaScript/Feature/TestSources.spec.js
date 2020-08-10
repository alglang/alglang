import '../setup';
import { fireEvent, render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Sources from '../../../resources/js/components/Sources';
import { sourceFactory } from '../factory';

const renderSources = props => render(Sources, {
  props: {
    url: '/api/sources',
    ...props
  }
});

const renderSourcesAndWait = async props => {
  const wrapper = renderSources(props);
  await waitForElementToBeRemoved(wrapper.getByLabelText('Loading'));
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
    const { queryByText } = await renderSourcesAndWait({ url: '/api/sources' });

    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
    expect(queryByText('Foo Bar 5432')).to.exist;
  });

  it('restricts the number of sources', async function () {
    const { queryByText } = await renderSourcesAndWait({
      url: '/api/sources',
      perPage: 2
    });

    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
    expect(queryByText('Foo Bar 5432')).to.not.exist;
  });

  it('filters sources', async function () {
    const { getByLabelText, queryByText } = await renderSourcesAndWait({ url: '/api/sources' });

    await fireEvent.input(getByLabelText('Filter'), {
      target: { value: 'John' }
    });

    expect(queryByText('Jane Doe 2000a')).to.not.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
    expect(queryByText('Foo Bar 5432')).to.not.exist;
  });

  it('loads the next page if it runs out of sources', async function () {
    const { getByLabelText, queryByText } = await renderSourcesAndWait({
      url: '/api/sources',
      perPage: 2
    });

    expect(queryByText('Jane Doe 2000b')).to.not.exist;

    await fireEvent.input(getByLabelText('Filter'), {
      target: { value: 'Jane' }
    });

    await waitForElementToBeRemoved(getByLabelText('Loading'), { timeout: 3000 });

    expect(queryByText('Jane Doe 2000b')).to.exist;
  });
});
