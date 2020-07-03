import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Morpheme/BasicDetails';
import { glossFactory, morphemeFactory, slotFactory } from '../factory';

describe('Morpheme/BasicDetails.vue', function () {
  describe('displaying universally required data', function () {
    it('shows its slot', function () {
      const props = {
        value: morphemeFactory({
          slot: { abv: 'SLOT' }
        })
      };

      const { getByLabelText, getByText } = render(BasicDetails, { props });

      expect(getByLabelText('Slot'));
      expect(getByText('SLOT'));
    });

    it('shows its glosses', function () {
      const props = {
        value: morphemeFactory({
          glosses: [
            {
              abv: 'FTV',
              name: 'formative'
            },
            {
              abv: 'N',
              name: 'N-suffix'
            }
          ]
        })
      };

      const { getByLabelText, getByText } = render(BasicDetails, { props });

      expect(getByLabelText('Gloss'));
      expect(getByText('FTV'));
      expect(getByText('N'));
      expect(getByText('(formative N-suffix)'));
    });

    it('shows no full text if none exists', function () {
      const props = {
        value: morphemeFactory({
          glosses: [
            glossFactory({ abv: 'FTV' })
          ]
        })
      };

      const { getByLabelText, getByText, queryByText } = render(BasicDetails, { props });

      expect(getByLabelText('Gloss'));
      expect(getByText('FTV'));
      expect(queryByText('()')).to.be.null;
    });

    it('hyperlinks its slot', function () {
      const props = {
        value: morphemeFactory({
          slot: slotFactory({
            abv: 'SLOT',
            url: '/slots/SLOT'
          })
        })
      };

      const { getByText } = render(BasicDetails, { props });

      expect(getByText('SLOT')).to.have.tagName('a');
      expect(getByText('SLOT')).to.have.attribute('href', '/slots/SLOT');
    });

    it('hyperlinks glosses with a url', function () {
      const props = {
        value: morphemeFactory({
          glosses: [
            glossFactory({
              abv: 'FTV',
              url: '/glosses/FTV'
            })
          ]
        })
      };

      const { getByText } = render(BasicDetails, { props });

      expect(getByText('FTV')).to.have.tagName('a');
      expect(getByText('FTV')).to.have.attribute('href', '/glosses/FTV');
    });

    it('does not hyperlink glosses without a url', function () {
      const props = {
        value: morphemeFactory({
          glosses: [
            glossFactory({
              abv: 'FTV',
              url: null
            })
          ]
        })
      };

      const { getByText } = render(BasicDetails, { props });

      expect(getByText('FTV')).to.have.tagName('span');
      expect(getByText('FTV')).to.not.have.attribute('href');
    });
  });

  describe('displaying allomorphy notes', function () {
    describe('when it has allomorphy notes', function () {
      it('displays its allomorphy notes', function () {
        const props = {
          value: morphemeFactory({
            allomorphy_notes: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Allomorphy'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no allomorphy notes', function () {
      it('does not display its allomorphy notes', function () {
        const props = {
          value: morphemeFactory({ allomorphy_notes: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Allomorphy')).to.be.null;
      });
    });
  });

  describe('displaying historical notes', function () {
    describe('when it has historical notes', function () {
      it('displays its historical notes', function () {
        const props = {
          value: morphemeFactory({
            historical_notes: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Historical notes'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no historical notes', function () {
      it('does not display its historical notes', function () {
        const props = {
          value: morphemeFactory({ historical_notes: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Historical notes')).to.be.null;
      });
    });
  });

  describe('displaying private notes', function () {
    describe('when it has private notes', function () {
      it('displays its private notes', function () {
        const props = {
          value: morphemeFactory({
            private_notes: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Private notes'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no private notes', function () {
      it('does not display its private notes', function () {
        const props = {
          value: morphemeFactory({ private_notes: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Private notes')).to.be.null;
      });
    });
  });
});
