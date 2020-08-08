import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Examples from '../../../resources/js/components/Examples';
import { exampleFactory } from '../factory';

const renderExamples = props => render(Examples, {
  props: {
    url: '/api/examples',
    ...props
  }
});

const renderExamplesAndWait = async props => {
  const wrapper = await renderExamples(props);
  await waitForElementToBeRemoved(wrapper.getByLabelText('Loading'));
  return wrapper;
};

describe('Examples.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('displays loading text when it is first mounted', function () {
    const { queryByLabelText } = renderExamples();

    expect(queryByLabelText('Loading')).to.exist;
  });

  it('loads examples', async function () {
    moxios.stubRequest('/api/examples', {
      response: {
        data: [
          exampleFactory({ shape: 'foobar' }),
          exampleFactory({ shape: 'bazbang' })
        ]
      }
    });

    const { queryByText } = await renderExamplesAndWait({
      url: '/api/examples'
    });

    expect(queryByText('foobar')).to.exist;
    expect(queryByText('bazbang')).to.exist;
  });

  it('informs the user when no examples exist', async function () {
    moxios.stubRequest('/api/examples', {
      response: { data: [] }
    });

    const { queryByText } = await renderExamplesAndWait({
      url: '/api/examples'
    });

    expect(queryByText('No morphemes')).to.exist;
  });
});
