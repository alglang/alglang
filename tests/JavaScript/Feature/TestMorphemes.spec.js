import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Morphemes from '../../../resources/js/components/Morphemes';
import { morphemeFactory } from '../factory';

const renderMorphemes = props => render(Morphemes, { props });

describe('Morphemes.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('displays loading text when it is first mounted', function () {
    const { queryByLabelText } = renderMorphemes({
      url: '/api/languages/tl/morphemes'
    });

    expect(queryByLabelText('Loading')).to.exist;
  });

  it('loads morphemes', async function () {
    moxios.stubRequest('/api/languages/tl/morphemes', {
      response: {
        data: [
          morphemeFactory({ shape: 'aa-' }),
          morphemeFactory({ shape: 'ab-' })
        ]
      }
    });

    const { getByLabelText, queryByText } = renderMorphemes({
      url: '/api/languages/tl/morphemes'
    });

    await waitForElementToBeRemoved(getByLabelText('Loading'));

    expect(queryByText('aa-')).to.exist;
    expect(queryByText('ab-')).to.exist;
  });

  it('informs the user when no morphemes exist', async function () {
    moxios.stubRequest('/api/languages/tl/morphemes', {
      response: { data: [] }
    });

    const { getByLabelText, queryByText } = renderMorphemes({
      url: '/api/languages/tl/morphemes'
    });

    await waitForElementToBeRemoved(getByLabelText('Loading'));

    expect(queryByText('No morphemes')).to.exist;
  });
});
