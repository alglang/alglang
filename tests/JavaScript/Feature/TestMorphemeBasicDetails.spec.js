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

  describe('displaying description', function () {
    describe('when it has a description', function () {
      it('displays its description', function () {
        const props = {
          value: morphemeFactory({
            description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Description'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no description', function () {
      it('does not display its description', function () {
        const props = {
          value: morphemeFactory({ description: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Description')).to.be.null;
      });
    });
  });
});
