import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Morpheme/BasicDetails';
import { glossFactory, morphemeFactory, slotFactory } from '../factory';

const renderBasicDetails = props => render(BasicDetails, { props });

describe('Morpheme/BasicDetails.vue', function () {
  describe('displaying universally required data', function () {
    it('shows its slot', function () {
      const { getByLabelText } = renderBasicDetails({
        value: morphemeFactory({
          slot: { abv: 'SLOT' }
        })
      });

      expect(getByLabelText('Slot')).to.contain.trimmed.text('SLOT');
    });

    it('shows its glosses', function () {
      const { getByLabelText } = renderBasicDetails({
        value: morphemeFactory({
          glosses: [
            glossFactory({ abv: 'FTV', name: 'formative' }),
            glossFactory({ abv: 'N', name: 'N-suffix' })
          ]
        })
      });

      const glossField = getByLabelText('Gloss');
      expect(glossField).to.contain.trimmed.text('FTV');
      expect(glossField).to.contain.trimmed.text('N');
      expect(glossField).to.contain.trimmed.text('(formative N-suffix)');
    });

    it('shows no full text if none exists', function () {
      const { getByLabelText } = renderBasicDetails({
        value: morphemeFactory({
          glosses: [
            glossFactory({ abv: 'FTV' })
          ]
        })
      });

      expect(getByLabelText('Gloss')).to.contain.trimmed.text('FTV');
      expect(getByLabelText('Gloss')).to.not.contain.trimmed.text('()');
    });

    it('hyperlinks its slot', function () {
      const { getByText } = renderBasicDetails({
        value: morphemeFactory({
          slot: slotFactory({
            abv: 'SLOT',
            url: '/slots/SLOT'
          })
        })
      });

      expect(getByText('SLOT')).to.have.tagName('a');
      expect(getByText('SLOT')).to.have.attribute('href', '/slots/SLOT');
    });

    it('colours its slot', function () {
      const { getByText } = renderBasicDetails({
        value: morphemeFactory({
          slot: slotFactory({
            abv: 'SLOT',
            colour: '#ff0000'
          })
        })
      });

      expect(getByText('SLOT')).to.have.attribute('style', 'color: rgb(255, 0, 0);');
    });

    it('hyperlinks glosses with a url', function () {
      const { getByText } = renderBasicDetails({
        value: morphemeFactory({
          glosses: [
            glossFactory({
              abv: 'FTV',
              url: '/glosses/FTV'
            })
          ]
        })
      });

      expect(getByText('FTV')).to.have.tagName('a');
      expect(getByText('FTV')).to.have.attribute('href', '/glosses/FTV');
    });

    it('does not hyperlink glosses without a url', function () {
      const { getByText } = renderBasicDetails({
        value: morphemeFactory({
          glosses: [
            glossFactory({
              abv: 'FTV',
              url: null
            })
          ]
        })
      });

      expect(getByText('FTV')).to.have.tagName('span');
      expect(getByText('FTV')).to.not.have.attribute('href');
    });
  });

  describe('displaying allomorphy notes', function () {
    describe('when it has allomorphy notes', function () {
      it('displays its allomorphy notes', function () {
        const { getByLabelText } = renderBasicDetails({
          value: morphemeFactory({
            allomorphy_notes: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        });

        expect(getByLabelText('Allomorphy')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
      });
    });

    describe('when it has no allomorphy notes', function () {
      it('does not display its allomorphy notes', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: morphemeFactory({ allomorphy_notes: null })
        });
        expect(queryByLabelText('Allomorphy')).to.not.exist;
      });
    });
  });

  describe('displaying historical notes', function () {
    describe('when it has historical notes', function () {
      it('displays its historical notes', function () {
        const { getByLabelText } = renderBasicDetails({
          value: morphemeFactory({
            historical_notes: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        });

        expect(getByLabelText('Historical notes')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
      });
    });

    describe('when it has no historical notes', function () {
      it('does not display its historical notes', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: morphemeFactory({ historical_notes: null })
        });

        expect(queryByLabelText('Historical notes')).to.not.exist;
      });
    });
  });

  describe('displaying private notes', function () {
    describe('when it has private notes', function () {
      it('displays its private notes', function () {
        const { getByLabelText } = renderBasicDetails({
          value: morphemeFactory({
            private_notes: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        });

        expect(getByLabelText('Private notes')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
      });
    });

    describe('when it has no private notes', function () {
      it('does not display its private notes', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: morphemeFactory({ private_notes: null })
        });

        expect(queryByLabelText('Private notes')).to.not.exist;
      });
    });
  });
});
