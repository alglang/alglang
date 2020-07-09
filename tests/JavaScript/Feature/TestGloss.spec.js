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

    const { queryByText } = render(Gloss, { props });
    expect(queryByText('GLS')).to.exist;
  });

  it('shows its basic details by default', function () {
    const props = {
      gloss: glossFactory({
        name: 'Gloss name',
        description: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
      })
    };

    const { queryByText } = render(Gloss, { props });

    expect(queryByText('Gloss name')).to.exist;
    expect(queryByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam')).to.exist;
  });
});
