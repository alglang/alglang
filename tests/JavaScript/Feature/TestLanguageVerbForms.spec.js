import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import VerbForms from '../../../resources/js/components/VerbForms';
import { verbFormFactory } from '../factory';

const renderVerbForms = props => render(VerbForms, {
  props: {
    url: '/',
    ...props
  }
});

const renderVerbFormsAndWaitForLoad = async props => {
  const wrapper = await renderVerbForms(props);
  await waitForElementToBeRemoved(wrapper.getByLabelText('Loading'));
  return wrapper;
};

describe('VerbForms.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('indicates that it is loading when it is first mounted', function () {
    const { queryByLabelText } = renderVerbForms();

    expect(queryByLabelText('Loading')).to.exist;
  });

  it('loads verb forms', async function () {
    moxios.stubRequest('/api/languages/tl/verb-forms', {
      response: {
        data: [
          verbFormFactory({ shape: 'V-a' }),
          verbFormFactory({ shape: 'V-b' })
        ]
      }
    });

    const { queryByText } = await renderVerbFormsAndWaitForLoad({ url: '/api/languages/tl/verb-forms' });

    expect(queryByText('V-a')).to.exist;
    expect(queryByText('V-b')).to.exist;
  });

  it('informs the user when no verb forms exist', async function () {
    moxios.stubRequest('/api/languages/tl/verb-forms', {
      response: { data: [] }
    });

    const { queryByText } = await renderVerbFormsAndWaitForLoad({ url: '/api/languages/tl/verb-forms' });

    expect(queryByText('No verb forms')).to.exist;
  });
});
