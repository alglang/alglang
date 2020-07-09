import { render, fireEvent } from '@testing-library/vue';
import { expect } from 'chai';

import DetailRow from '../../../resources/js/components/DetailRow';

const renderDetailRow = (props, slot = '') => render(DetailRow, {
  props: {
    label: 'Dummy label',
    ...props
  },
  scopedSlots: {
    default: slot
  }
});

describe('DetailRow.vue', function () {
  beforeEach(function () {
    this.oldLocation = window.location;
    delete window.location;
    window.location = new URL('http://localhost');
  });

  afterEach(function () {
    delete window.location;
    window.location = this.oldLocation;
  });

  it('labels itself based on its label prop', function () {
    const { queryByLabelText } = renderDetailRow({ label: 'Foo bar' });
    expect(queryByLabelText('Foo bar')).to.exist;
  });

  it('renders its slot data', function () {
    const { queryByText } = renderDetailRow({}, '<p>Bar</p>');
    expect(queryByText('Bar')).to.exist;
  });

  it('disables all links when disabled', async function () {
    const { getByText } = renderDetailRow({ disabled: true }, '<a href="#foo">Link</a>');
    expect(window.location.hash).to.equal('');

    await fireEvent.click(getByText('Link'));

    expect(window.location.hash).to.equal('');
  });
});
