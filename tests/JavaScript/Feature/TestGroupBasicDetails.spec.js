import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Group/BasicDetails';
import { groupFactory, languageFactory } from '../factory';

const renderBasicDetails = props => render(BasicDetails, { props });

describe('Group/BasicDetails.vue', function () {
  describe('displaying description', function () {
    describe('when it has a description', function () {
      it('shows its description', function () {
        const { getByLabelText } = renderBasicDetails({
          value: groupFactory({ description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
        });

        expect(getByLabelText('Description')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
      });
    });

    describe('when it has no description', function () {
      it('does not show a description', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: groupFactory({ description: null })
        });

        expect(queryByLabelText('Description')).to.not.exist;
      });
    });
  });

  describe('displaying locations', function () {
    describe('when it has at least one language with a position', function () {
      it('shows a map', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: groupFactory({
            languages: [
              languageFactory({
                position: { lat: 90, lng: 45 }
              })
            ]
          })
        });

        expect(queryByLabelText('Location')).to.exist;
      });
    });

    describe('when it has no languages', function () {
      it('does not show a map', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: groupFactory({
            languages: []
          })
        });

        expect(queryByLabelText('Location')).to.not.exist;
      });
    });

    describe('when it has languages but none have a position', function () {
      it('does not show a map', function () {
        const { queryByLabelText } = renderBasicDetails({
          value: groupFactory({
            languages: [languageFactory({ position: null })]
          })
        });

        expect(queryByLabelText('Location')).to.not.exist;
      });
    });
  });
});
