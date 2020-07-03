import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Morphemes from '../../../resources/js/components/Language/Morphemes';
import { morphemeFactory, languageFactory } from '../factory';

describe('Language/Morphemes.vue', function () {
  beforeEach(function () { moxios.install(); });

  afterEach(function () { moxios.uninstall(); });

  it('displays loading text when it is first mounted', function () {
    const props = {
      value: languageFactory({ url: '/languages/tl' })
    };

    const { getByText } = render(Morphemes, { props });

    expect(getByText('Loading...'));
  });

  it('loads morphemes', async function () {
    const props = {
      value: languageFactory({ url: '/languages/tl' })
    };

    const { getByText } = render(Morphemes, { props });

    moxios.stubRequest('/languages/tl/morphemes', {
      status: 200,
      response: {
        data: [
          morphemeFactory({ shape: 'aa-' }),
          morphemeFactory({ shape: 'ab-' })
        ]
      }
    });

    await waitForElementToBeRemoved(getByText('Loading...'));

    expect(getByText('aa-'));
    expect(getByText('ab-'));
  });
});
