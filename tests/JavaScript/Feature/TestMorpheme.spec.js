import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Morpheme from '../../../resources/js/components/Morpheme';
import {
  morphemeFactory,
  languageFactory
} from '../factory';

require('../setup');

describe('Morpheme.vue', function () {
  it('displays its shape and language in the header', function () {
    const props = {
      morpheme: morphemeFactory({
        shape: '-ak',
        language: languageFactory({ name: 'Test Language' })
      })
    };

    const { queryByText } = render(Morpheme, { props });

    expect(queryByText('-ak')).to.exist;
    expect(queryByText('Test Language')).to.exist;
  });
});
