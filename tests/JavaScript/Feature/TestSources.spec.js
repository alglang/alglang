import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
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
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('shows that it is loading when it first mounts', function () {
    const { queryByLabelText } = renderSources();

    expect(queryByLabelText('Loading')).to.exist;
  });

  it('loads sources', async function () {
    moxios.stubRequest('/api/sources', {
      response: {
        data: [
          sourceFactory({ short_citation: 'Jane Doe 2000a' }),
          sourceFactory({ short_citation: 'John Doe 1234b' })
        ]
      }
    });

    const { queryByText } = await renderSourcesAndWait({
      url: '/api/sources'
    });

    expect(queryByText('Jane Doe 2000a')).to.exist;
    expect(queryByText('John Doe 1234b')).to.exist;
  });
});
