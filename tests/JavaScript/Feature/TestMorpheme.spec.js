import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Morpheme from '../../../resources/js/components/Morpheme';
import BasicDetails from '../../../resources/js/components/Morpheme/BasicDetails';
import { glossFactory, morphemeFactory, languageFactory } from '../factory';

describe('Morpheme.vue', () => {
  it('displays its shape and language in the header', () => {
    const props = {
      morpheme: morphemeFactory({
        shape: '-ak',
        language: languageFactory({ name: 'Test Language' })
      })
    };

    const { getByText } = render(Morpheme, { props });

    expect(getByText('-ak'));
    expect(getByText('Test Language'));
  });
});

describe('Morpheme/BasicDetails.vue', () => {
  describe('displaying universally required data', () => {
    it('shows its slot', () => {
      const props = {
        value: morphemeFactory({
          slot: { abv: 'SLOT' }
        })
      };

      const { getByLabelText, getByText } = render(BasicDetails, { props });

      expect(getByLabelText('Slot'));
      expect(getByText('SLOT'));
    });

    it('shows its glosses', () => {
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

    it('shows no full text if none exists', () => {
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
  });

  describe('displaying description', () => {
    describe('when it has a description', () => {
      it('displays its description', () => {
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

    describe('when it has no description', () => {
      it('does not display its description', () => {
        const props = {
          value: morphemeFactory({ description: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Description')).to.be.null;
      });
    });
  });
});
