import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Slot/BasicDetails';
import { slotFactory } from '../factory';

describe('Slot/BasicDetails.vue', function () {
  describe('required details', function () {
    it('displays its full name', function () {
      const props = {
        value: slotFactory({ name: 'Slot name' })
      };

      const { getByLabelText, getByText } = render(BasicDetails, { props });

      expect(getByLabelText('Full name'));
      expect(getByText('Slot name'));
    });
  });

  describe('displaying description', function () {
    describe('when it has a description', function () {
      it('displays its description', function () {
        const props = {
          value: slotFactory({
            description: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Description'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no description', function () {
      it('does not display a description', function () {
        const props = {
          value: slotFactory({ description: null })
        };

        const { queryByText } = render(BasicDetails, { props });

        expect(queryByText('Description')).to.be.null;
      });
    });
  });
});
