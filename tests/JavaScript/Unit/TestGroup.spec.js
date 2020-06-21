import { render } from '@testing-library/vue';
import Group from '../../../resources/js/components/Group.vue';
import { expect } from 'chai';

describe('Group.vue', () => {
  it('requires a group prop', () => {
    expect(Group.props.group.required).to.be.true;
  });
});
