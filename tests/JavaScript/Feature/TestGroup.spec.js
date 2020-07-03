import { render } from '@testing-library/vue';
import { expect } from 'chai';

import Group from '../../../resources/js/components/Group';
import { groupFactory } from '../factory';

describe('Group.vue', function () {
  it('displays its detail page on initial render', function () {
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
