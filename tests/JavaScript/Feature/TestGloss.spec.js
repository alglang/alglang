import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Gloss from '../../../resources/js/components/Gloss';
import { glossFactory } from '../factory';

describe('Gloss.vue', function () {
  it('displays the gloss abbreviation in the header', function () {
    const props = {
      gloss: glossFactory({ abv: 'GLS' })
    };

    const { getByText } = render(Gloss, { props });

    expect(getByText('GLS'));
  });

  it('shows its basic details by default', function () {
    const props = {
      gloss: glossFactory({
        name: 'Gloss name',
        description: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
      })
    };

    const { getByText } = render(Gloss, { props });

    expect(getByText('Gloss name'));
    expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
  });
});
