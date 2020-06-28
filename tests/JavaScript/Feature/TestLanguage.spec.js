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
