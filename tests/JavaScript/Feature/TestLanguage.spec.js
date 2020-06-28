import { render } from '@testing-library/vue';
import Language from '../../../resources/js/components/Language.vue';
import { expect } from 'chai';

import { groupFactory, languageFactory } from '../factory';

describe('Language.vue', () => {
  it('displays its name in the header', () => {
    const props = {
      language: languageFactory({ name: 'Test Language' })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Language details'));
    expect(getByText('Test Language'));
  });

  it('displays its detail page on initial render', () => {
    const props = {
      language: languageFactory({ algo_code: 'TL' })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('TL'));  // The algonquianist code should only appear on the detail page
  });

  it('indicates that the language is reconstructed', () => {
    const props = {
      language: languageFactory({ reconstructed: true })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Reconstructed'));
  });
});

describe('Language/BasicDetails.vue', () => {
  describe('displaying universally required data', () => {
    it('shows its algonquianist code', () => {
        const props = {
          language: languageFactory({ algo_code: 'TL' })
        };

        const { getByLabelText, getByText } = render(Language, { props });

        expect(getByLabelText('Algonquianist code'));
        expect(getByText('TL'));
    });

    it('shows its group', () => {
        const props = {
          language: languageFactory({
            group: groupFactory({ name: 'Test Group' })
          })
        };

        const { getByLabelText, getByText } = render(Language, { props });

        expect(getByLabelText('Group'));
        expect(getByText('Test Group'));
    });
  });

  describe('displaying location', () => {
    describe('when it has a position', () => {
      it('shows a map', () => {
        const props = {
          language: languageFactory({
            position: { lat: 90, lng: 45 }
          })
        };

        const { getByLabelText } = render(Language, { props });

        expect(getByLabelText('Location'));
      });
    });

    describe('has no position', () => {
      it('does not show a map', () => {
        const props = {
          language: languageFactory({ position: null })
        };

        const { queryByLabelText } = render(Language, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });
  });

  describe('displaying children', () => {
    describe('when it has children', () => {
      it('shows its children', () => {
        const props = {
          language: languageFactory({
            children: [
              languageFactory({ name: 'Child Language 1' }),
              languageFactory({ name: 'Child Language 2' })
            ]
          })
        };

        const { getByLabelText, getByText } = render(Language, { props });

        expect(getByLabelText('Direct children'));
        expect(getByText('Child Language 1'));
        expect(getByText('Child Language 2'));
      });
    });

    describe('when it has no children', () => {
      it('does not show the children section', () => {
        const props = {
          language: languageFactory({ children: [] })
        };

        const { queryByLabelText } = render(Language, { props });

        expect(queryByLabelText('Direct children')).to.be.null;
      });
    });
  });
});
