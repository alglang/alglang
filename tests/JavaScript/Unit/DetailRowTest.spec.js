import { render, fireEvent } from '@testing-library/vue';
import { expect } from 'chai';

import DetailRow from '../../../resources/js/components/DetailRow';

describe('DetailRow.vue', function () {
  it('labels itself based on its label prop', function () {
    const props = { label: 'Foo bar' };

    const { getByLabelText } = render(DetailRow, { props });

    expect(getByLabelText('Foo bar'));
  });

  it('renders its slot data', function () {
    const props = { label: 'Foo' };
    const scopedSlots = { default: '<p>Bar</p>' };

    const { getByText } = render(DetailRow, { props, scopedSlots });

    expect(getByText('Bar'));
  });

  it('disables all links when disabled', async function () {
    const props = { label: '', disabled: true };
    const scopedSlots = { default: '<a href="#foo">Link</a>' };

    const { container, getByText } = render(DetailRow, { props, scopedSlots });
    const { location } = container.getRootNode();
    expect(location.hash).to.equal('');

    await fireEvent.click(getByText('Link'));

    expect(location.hash).to.equal('');
  });
});
