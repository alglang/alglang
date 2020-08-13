import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import NominalFormCard from '../../../resources/js/components/NominalFormCard';
import { nominalFormFactory } from '../factory';

const renderNominalFormCard = props => render(NominalFormCard, {
  props: {
    ...props
  }
});

describe('NominalFormCard.vue', function () {
  it('presents its shape', function () {
    const { queryByText } = renderNominalFormCard({
      nominalForm: nominalFormFactory({ shape: 'N-a' })
    });

    expect(queryByText('N-a')).to.exist;
  });

  it('hyperlinks the card', function () {
    const { container } = renderNominalFormCard({
      nominalForm: nominalFormFactory({ url: '/languages/tl/nominal-forms/N-a' })
    });

    expect(container.firstChild).to.have.tagName('a');
    expect(container.firstChild).to.have.attribute('href', '/languages/tl/nominal-forms/N-a');
  });
});
