import { render } from '@testing-library/vue';
import Group from '../../../resources/js/components/Group.vue';
import { expect } from 'chai';

describe('Group.vue', () => {
  it('displays its detail page on initial render', () => {
    const props = {
      group: {
        name: 'Test Group',
        description: 'Lorem ipsum dolor sit amet'
      }
    };

    const { getByText } = render(Group, { props });

    expect(getByText('Group details'));
    expect(getByText('Test Group'));
    expect(getByText('Lorem ipsum dolor sit amet'));
  });
});
