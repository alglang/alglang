import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Slot/BasicDetails';
import { slotFactory } from '../factory';

const renderBasicDetails = props => render(BasicDetails, { props });

describe('Slot/BasicDetails.vue', function () {
  describe('required details', function () {
    it('displays its full name', function () {
      const { getByLabelText } = renderBasicDetails({
        value: slotFactory({ name: 'Slot name' })
      });

      expect(getByLabelText('Full name')).to.contain.trimmed.text('Slot name');
    });
  });

  describe('displaying description', function () {
    describe('when it has a description', function () {
      it('displays its description', function () {
        const { getByLabelText } = renderBasicDetails({
          value: slotFactory({
            description: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        });

        expect(getByLabelText('Description')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
      });
    });

    describe('when it has no description', function () {
      it('does not display a description', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: slotFactory({ description: null })
        });

        expect(queryByLabelText('Description')).to.not.exist;
      });
    });
  });
});
