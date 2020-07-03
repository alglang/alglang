import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Slot from '../../../resources/js/components/Slot';
import { slotFactory } from '../factory';

describe('Slot.vue', function () {
  it('displays the slot abbreviation in the header', function () {
    const props = {
      morphSlot: slotFactory({
        abv: 'SLT',
        colour: '#ff0000'
      })
    };

    const { getByText } = render(Slot, { props });

    expect(getByText('SLT')).to.have.attribute('style', 'color: rgb(255, 0, 0);');
  });

  it('shows its basic details by default', function () {
    const props = {
      morphSlot: slotFactory({
        name: 'Slot name',
        description: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
      })
    };

    const { getByText } = render(Slot, { props });

    expect(getByText('Slot name'));
    expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
  });
});
