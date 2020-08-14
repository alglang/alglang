import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import NominalForms from '../../../resources/js/components/NominalForms';
import { nominalFormFactory } from '../factory';

const renderNominalForms = props => render(NominalForms, {
  props: {
    url: '/',
    ...props
  }
});

const renderNominalFormsAndWaitForLoad = async props => {
  const wrapper = await renderNominalForms(props);
  await waitForElementToBeRemoved(wrapper.getByLabelText('Loading'));
  return wrapper;
};

describe('NominalForms.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('indicates that it is loading when it is first mounted', function () {
    const { queryByLabelText } = renderNominalForms();

    expect(queryByLabelText('Loading')).to.exist;
  });

  it('loads nominal forms', async function () {
    moxios.stubRequest('/api/languages/tl/nominal-forms', {
      response: {
        data: [
          nominalFormFactory({ shape: 'N-a' }),
          nominalFormFactory({ shape: 'N-b' })
        ]
      }
    });

    const { queryByText } = await renderNominalFormsAndWaitForLoad({ url: '/api/languages/tl/nominal-forms' });

    expect(queryByText('N-a')).to.exist;
    expect(queryByText('N-b')).to.exist;
  });

  it('informs the user when no nominal forms exist', async function () {
    moxios.stubRequest('/api/languages/tl/nominal-forms', {
      response: { data: [] }
    });

    const { queryByText } = await renderNominalFormsAndWaitForLoad({ url: '/api/languages/tl/nominal-forms' });

    expect(queryByText('No nominal forms')).to.exist;
  });
});
