import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import MorphemeCard from '../../../resources/js/components/MorphemeCard';
import { glossFactory, morphemeFactory, slotFactory } from '../factory';

const renderMorphemeCard = props => render(MorphemeCard, { props });

describe('MorphemeCard.vue', function () {
  it('hyperlinks the card', function () {
    const { container } = renderMorphemeCard({
      morpheme: morphemeFactory({ url: '/morphemes/foo' })
    });

    expect(container.firstChild).to.have.tagName('a');
    expect(container.firstChild).to.have.attribute('href', '/morphemes/foo');
  });

  it('displays a morpheme shape', function () {
    const { queryByText } = renderMorphemeCard({
      morpheme: morphemeFactory({ shape: 'ak-' })
    });

    expect(queryByText('ak-')).to.exist;
  });

  it('displays a morpheme slot', function () {
    const { getByText } = renderMorphemeCard({
      morpheme: morphemeFactory({
        slot: slotFactory({
          abv: 'SLT',
          url: '/slots/SLT',
          colour: '#ff0000'
        })
      })
    });

    expect(getByText('SLT')).to.have.tagName('a');
    expect(getByText('SLT')).to.have.attribute('href', '/slots/SLT');
    expect(getByText('SLT')).to.have.attribute('style', 'color: rgb(255, 0, 0);');
  });

  it('displays morpheme glosses', function () {
    const { queryByText } = renderMorphemeCard({
      morpheme: morphemeFactory({
        glosses: [
          glossFactory({ abv: 'G1' }),
          glossFactory({ abv: 'G2' })
        ]
      })
    });

    expect(queryByText('G1')).to.exist;
    expect(queryByText('G2')).to.exist;
  });
});
