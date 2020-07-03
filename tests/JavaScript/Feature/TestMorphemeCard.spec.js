import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import MorphemeCard from '../../../resources/js/components/MorphemeCard';
import { glossFactory, morphemeFactory, slotFactory } from '../factory';

describe('MorphemeCard.vue', function () {
  it('hyperlinks the card', function () {
    const props = {
      morpheme: morphemeFactory({ url: '/morphemes/foo' })
    };

    const { container } = render(MorphemeCard, { props });

    expect(container.firstChild).to.have.tagName('a');
    expect(container.firstChild).to.have.attribute('href', '/morphemes/foo');
  });

  it('displays a morpheme shape', function () {
    const props = {
      morpheme: morphemeFactory({ shape: 'ak-' })
    };

    const { getByText } = render(MorphemeCard, { props });

    expect(getByText('ak-'));
  });

  it('displays a morpheme slot', function () {
    const props = {
      morpheme: {
        slot: slotFactory({
          abv: 'SLT',
          url: '/slots/SLT',
          colour: '#ff0000'
        })
      }
    };

    const { getByText } = render(MorphemeCard, { props });

    expect(getByText('SLT')).to.have.tagName('a');
    expect(getByText('SLT')).to.have.attribute('href', '/slots/SLT');
    expect(getByText('SLT')).to.have.attribute('style', 'color: rgb(255, 0, 0);');
  });

  it('displays morpheme glosses', function () {
    const props = {
      morpheme: morphemeFactory({
        glosses: [
          glossFactory({ abv: 'G1' }),
          glossFactory({ abv: 'G2' })
        ]
      })
    };

    const { getByText } = render(MorphemeCard, { props });

    expect(getByText('G1'));
    expect(getByText('G2'));
  });
});
