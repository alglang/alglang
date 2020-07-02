import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Group/BasicDetails';
import { groupFactory, languageFactory } from '../factory';

describe('Group/BasicDetails.vue', function () {
  describe('displaying description', function () {
    describe('when it has a description', function () {
      it('shows its description', function () {
        const props = {
          value: groupFactory({ description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Description'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no description', function () {
      it('does not show a description', function () {
        const props = {
          value: groupFactory({ description: null })
        };

        const { queryByText } = render(BasicDetails, { props });

        expect(queryByText('Description')).to.be.null;
      });
    });
  });

  describe('displaying locations', function () {
    describe('when it has at least one language with a position', function () {
      it('shows a map', function () {
        const props = {
          value: groupFactory({
            languages: [
              languageFactory({
                position: { lat: 90, lng: 45 }
              })
            ]
          })
        };

        const { getByLabelText } = render(BasicDetails, { props });

        expect(getByLabelText('Location'));
      });
    });

    describe('when it has no languages', function () {
      it('does not show a map', function () {
        const props = {
          value: groupFactory({
            languages: []
          })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });

    describe('when it has languages but none have a position', function () {
      it('does not show a map', function () {
        const props = {
          value: groupFactory({
            languages: [languageFactory({ position: null })]
          })
        };
        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });
  });
});
