import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Gloss/BasicDetails';
import { glossFactory } from '../factory';

const renderBasicDetails = props => render(BasicDetails, { props });

describe('Gloss/BasicDetails.vue', function () {
  describe('required details', function () {
    it('displays its full name', function () {
      const { getByLabelText } = renderBasicDetails({
        value: glossFactory({ name: 'Gloss name' })
      });

      expect(getByLabelText('Full name')).to.contain.trimmed.text('Gloss name');
    });
  });

  describe('displaying description', function () {
    describe('when it has a description', function () {
      it('displays its description', function () {
        const { getByLabelText } = renderBasicDetails({
          value: glossFactory({
            description: '<p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam</p>'
          })
        });

        expect(getByLabelText('Description')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
      });
    });

    describe('when it has no description', function () {
      it('does not display a description', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: glossFactory({ description: null })
        });

        expect(queryByLabelText('Description')).to.not.exist;
      });
    });
  });
});
