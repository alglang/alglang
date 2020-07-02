import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Language/BasicDetails';
import { groupFactory, languageFactory } from '../factory';

describe('Language/BasicDetails.vue', function () {
  describe('displaying universally required data', function () {
    it('shows its algonquianist code', function () {
      const props = {
        value: languageFactory({ algo_code: 'TL' })
      };

      const { getByLabelText, getByText } = render(BasicDetails, { props });

      expect(getByLabelText('Algonquianist code'));
      expect(getByText('TL'));
    });

    it('shows its group', function () {
      const props = {
        value: languageFactory({
          group: groupFactory({ name: 'Test Group' })
        })
      };

      const { getByLabelText, getByText } = render(BasicDetails, { props });

      expect(getByLabelText('Group'));
      expect(getByText('Test Group'));
    });
  });

  describe('displaying location', function () {
    describe('when it has a position', function () {
      it('shows a map', function () {
        const props = {
          value: languageFactory({
            position: { lat: 90, lng: 45 }
          })
        };

        const { getByLabelText } = render(BasicDetails, { props });

        expect(getByLabelText('Location'));
      });
    });

    describe('has no position', function () {
      it('does not show a map', function () {
        const props = {
          value: languageFactory({ position: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });
  });

  describe('displaying children', function () {
    describe('when it has children', function () {
      it('shows its children', function () {
        const props = {
          value: languageFactory({
            children: [
              languageFactory({ name: 'Child Language 1' }),
              languageFactory({ name: 'Child Language 2' })
            ]
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Direct children'));
        expect(getByText('Child Language 1'));
        expect(getByText('Child Language 2'));
      });
    });

    describe('when it has no children', function () {
      it('does not show the children section', function () {
        const props = {
          value: languageFactory({ children: [] })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Direct children')).to.be.null;
      });
    });
  });

  describe('displaying notes', function () {
    describe('when it has notes', function () {
      it('shows its notes', function () {
        const props = {
          value: languageFactory({ notes: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Notes'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no notes', function () {
      it('does not show its notes', function () {
        const props = {
          value: languageFactory({ notes: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Notes')).to.be.null;
      });
    });

    describe('when it has empty notes', function () {
      it('does not show its notes', function () {
        const props = {
          value: languageFactory({ notes: '' })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Notes')).to.be.null;
      });
    });
  });
});
