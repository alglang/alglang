import { render } from '@testing-library/vue';
import Language from '../../../resources/js/components/Language.vue';
import { expect } from 'chai';

describe('Language.vue', () => {
  it('displays its detail page on initial render', () => {
    const props = {
      language: {
        name: 'Test Language',
        algo_code: 'TL',
        group: {}
      },
      gmapsApiKey: ''
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Language details'));
    expect(getByText('Test Language'));
    expect(getByText('TL'));
  });

  it('indicates that the language is reconstructed', () => {
    const props = {
      language: {
        name: 'Test Language',
        algo_code: 'TL',
        reconstructed: true,
        group: {}
      }
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Reconstructed'));
  });
});

describe('Language/BasicDetails.vue', () => {
  describe('when it has children', () => {
    it('shows its children', () => {
      const props = {
        language: {
          name: 'Test Language',
          algo_code: 'TL',
          reconstructed: true,
          group: {},
          children: [
            {
              name: 'Child Language 1'
            },
            {
              name: 'Child Language 2'
            }
          ]
        }
      };

      const { getByText } = render(Language, { props });

      expect(getByText('Direct children'));
      expect(getByText('Child Language 1'));
      expect(getByText('Child Language 2'));
    });
  });

  describe('when it has no children', () => {
    it('does not show the children section', () => {
      const props = {
        language: {
          name: 'Test Language',
          algo_code: 'TL',
          reconstructed: true,
          group: {},
          children: []
        }
      };

      const { queryByText } = render(Language, { props });

      expect(queryByText('Direct children')).to.be.null;
    });
  });
});
