import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Group from '../../../resources/js/components/Group.vue';
import BasicDetails from '../../../resources/js/components/Group/BasicDetails';
import { groupFactory, languageFactory } from '../factory';

describe('Group.vue', () => {
  it('displays its detail page on initial render', () => {
    const props = {
      group: groupFactory({
        name: 'Test Group',
        description: 'Lorem ipsum dolor sit amet'
      }),
      pages: []
    };

    const { getByText } = render(Group, { props });

    expect(getByText('Group details'));
    expect(getByText('Test Group languages'));
    expect(getByText('Lorem ipsum dolor sit amet'));
  });
});

describe('Group/BasicDetails.vue', () => {
  describe('displaying description', () => {
    describe('when it has a description', () => {
      it('shows its description', () => {
        const props = {
          value: groupFactory({ description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Description'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no description', () => {
      it('does not show a description', () => {
        const props = {
          value: groupFactory({ description: null })
        };

        const { queryByText } = render(BasicDetails, { props });

        expect(queryByText('Description')).to.be.null;
      });
    });
  });

  describe('displaying locations', () => {
    describe('when it has at least one language with a position', () => {
      it('shows a map', () => {
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

    describe('when it has no languages', () => {
      it('does not show a map', () => {
        const props = {
          value: groupFactory({
            languages: []
          })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });

    describe('when it has languages but none have a position', () => {
      it('does not show a map', () => {
        const props = {
          value: groupFactory({
            languages: [ languageFactory({ position: null }) ]
          })
        };
        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });
  });
});
