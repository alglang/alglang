import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Morphemes from '../../../resources/js/components/Language/Morphemes';
import { morphemeFactory } from '../factory';

const renderMorphemes = props => render(Morphemes, { props });

describe('Language/Morphemes.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('displays loading text when it is first mounted', function () {
    const { queryByText } = renderMorphemes({
      url: '/api/languages/tl/morphemes'
      // language: languageFactory({ url: '/languages/tl' })
    });

    expect(queryByText('Loading...')).to.exist;
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
      // language: languageFactory({ url: '/languages/tl' })
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
      // language: languageFactory({ url: '/languages/tl' })
      url: '/api/languages/tl/morphemes'
    });

    await waitForElementToBeRemoved(getByLabelText('Loading'));

    expect(queryByText('No morphemes')).to.exist;
  });
});
