import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import VerbFormCard from '../../../resources/js/components/VerbFormCard';
import { verbFormFactory } from '../factory';

const renderVerbFormCard = props => render(VerbFormCard, {
  props: {
    ...props
  }
});

describe('VerbFormCard.vue', function () {
  it('presents its shape', function () {
    const { queryByText } = renderVerbFormCard({
      verbForm: verbFormFactory({ shape: 'V-a' })
    });

    expect(queryByText('V-a')).to.exist;
  });

  it('hyperlinks the card', function () {
    const { container } = renderVerbFormCard({
      verbForm: verbFormFactory({ url: '/languages/tl/verb-forms/V-a' })
    });

    expect(container.firstChild).to.have.tagName('a');
    expect(container.firstChild).to.have.attribute('href', '/languages/tl/verb-forms/V-a');
  });
});
