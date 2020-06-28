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
  it('shows its algonquianist code', () => {
      const props = {
        language: {
          name: 'Test Language',
          algo_code: 'TL',
          group: {}
        }
      };

      const { getByLabelText, getByText } = render(Language, { props });

      expect(getByLabelText('Algonquianist code'));
      expect(getByText('TL'));
  });

  it('shows its group', () => {
      const props = {
        language: {
          name: 'Test Language',
          algo_code: 'TL',
          group: {
            name: 'Test Group'
          }
        }
      };

      const { getByLabelText, getByText } = render(Language, { props });

      expect(getByLabelText('Group'));
      expect(getByText('Test Group'));
  });

  describe('when it has a position', () => {
    it('it shows a map', () => {
      const props = {
        language: {
          name: 'Test Language',
          algo_code: 'TL',
          group: {},
          position: {
            lat: 90,
            lng: 45
          }
        }
      };

      const { getByLabelText } = render(Language, { props });

      expect(getByLabelText('Location'));
    });
  });

  describe('it has no position', () => {
    it('it dow not show a map', () => {
      const props = {
        language: {
          name: 'Test Language',
          algo_code: 'TL',
          group: {}
        }
      };

      const { queryByLabelText } = render(Language, { props });

      expect(queryByLabelText('Location')).to.be.null;
    });
  });

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

      const { getByLabelText, getByText } = render(Language, { props });

      expect(getByLabelText('Direct children'));
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

      const { queryByLabelText } = render(Language, { props });

      expect(queryByLabelText('Direct children')).to.be.null;
    });
  });
});
