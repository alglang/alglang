import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Morphemes from '../../../resources/js/components/Language/Morphemes';
import { morphemeFactory, languageFactory } from '../factory';

const renderMorphemes = props => render(Morphemes, { props });

describe('Language/Morphemes.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('displays loading text when it is first mounted', function () {
    const { queryByText } = renderMorphemes({
      value: languageFactory({ url: '/languages/tl' })
    });

    expect(queryByText('Loading...')).to.exist;
  });

  it('loads morphemes', async function () {
    const { getByText, queryByText } = renderMorphemes({
      value: languageFactory({ url: '/languages/tl' })
    });

    moxios.stubRequest('/api/languages/tl/morphemes', {
      response: {
        data: [
          morphemeFactory({ shape: 'aa-' }),
          morphemeFactory({ shape: 'ab-' })
        ]
      }
    });

    await waitForElementToBeRemoved(getByText('Loading...'));

    expect(queryByText('aa-')).to.exist;
    expect(queryByText('ab-')).to.exist;
  });
});
