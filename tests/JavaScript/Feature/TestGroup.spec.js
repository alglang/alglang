import { render } from '@testing-library/vue';
import Group from '../../../resources/js/components/Group.vue';
import { expect } from 'chai';

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
          group: groupFactory({ description: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
        };

        const { getByLabelText, getByText } = render(Group, { props });

        expect(getByLabelText('Description'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no description', () => {
      it('does not show a description', () => {
        const props = {
          group: groupFactory({ description: null })
        };

        const { queryByText } = render(Group, { props });

        expect(queryByText('Description')).to.be.null;
      });
    });
  });

  describe('displaying locations', () => {
    describe('when it has at least one language with a position', () => {
      it('shows a map', () => {
        const props = {
          group: groupFactory({
            languages: [
              languageFactory({
                position: { lat: 90, lng: 45 }
              })
            ]
          })
        };

        const { getByLabelText } = render(Group, { props });

        expect(getByLabelText('Location'));
      });
    });

    describe('when it has no languages', () => {
      it('does not show a map', () => {
        const props = {
          group: groupFactory({
            languages: []
          })
        };

        const { queryByLabelText } = render(Group, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });

    describe('when it has languages but none have a position', () => {
      it('does not show a map', () => {
        const props = {
          group: groupFactory({
            languages: [ languageFactory({ position: null }) ]
          })
        };
        const { queryByLabelText } = render(Group, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });
  });
});
