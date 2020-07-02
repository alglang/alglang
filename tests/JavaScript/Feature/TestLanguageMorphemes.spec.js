import '../setup';
import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Morphemes from '../../../resources/js/components/Language/Morphemes';
import { glossFactory, languageFactory, slotFactory } from '../factory';

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
          {
            shape: 'aa-',
            slot: slotFactory({
              abv: 'SLT',
              url: '/slots/SLT'
            }),
            glosses: [
              glossFactory({
                abv: 'G1',
                url: '/glosses/G1'
              }),
              glossFactory({
                abv: 'G2'
              })
            ]
          }
        ]
      }
    });

    await waitForElementToBeRemoved(getByText('Loading...'));

    expect(getByText('aa-'));

    expect(getByText('SLT')).to.have.tagName('a');
    expect(getByText('SLT')).to.have.attribute('href', '/slots/SLT');

    expect(getByText('G1')).to.have.tagName('a');
    expect(getByText('G1')).to.have.attribute('href', '/glosses/G1');

    expect(getByText('G2')).to.have.tagName('span');
  });
});
