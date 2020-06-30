import { render } from '@testing-library/vue';
import DetailRow from '../../../resources/js/components/DetailRow';
import { expect } from 'chai';

describe('DetailRow.vue', () => {
  it('labels itself based on its label prop', () => {
    const props = { label: 'Foo bar' };

    const { getByLabelText } = render(DetailRow, { props });

    expect(getByLabelText('Foo bar'));
  });

  it('renders its slot data', () => {
    const props = { label: 'Foo' };
    const scopedSlots = { default: '<p>Bar</p>' };

    const { getByText } = render(DetailRow, { props, scopedSlots });

    expect(getByText('Bar'));
  });
});
