import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Language from '../../../resources/js/components/Language';
import { languageFactory } from '../factory';

describe('Language.vue', function () {
  it('displays its name in the header', function () {
    const props = {
      language: languageFactory({ name: 'Test Language' })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Language details'));
    expect(getByText('Test Language'));
  });

  it('displays its detail page on initial render', function () {
    const props = {
      language: languageFactory({ algo_code: 'TL' })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('TL')); // The algonquianist code should only appear on the detail page
  });

  it('indicates that the language is reconstructed', function () {
    const props = {
      language: languageFactory({ reconstructed: true })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Reconstructed'));
  });
});
