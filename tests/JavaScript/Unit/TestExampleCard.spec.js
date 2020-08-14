import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import ExampleCard from '../../../resources/js/components/ExampleCard';
import { exampleFactory, verbFormFactory } from '../factory';

const renderExampleCard = props => render(ExampleCard, { props });

describe('ExampleCard.vue', function () {
  it('presents its shape', function () {
    const { queryByText } = renderExampleCard({
      example: exampleFactory({ shape: 'foobar' })
    });

    expect(queryByText('foobar')).to.exist;
  });

  it('presents its form\'s shape', function () {
    const { queryByText } = renderExampleCard({
      example: exampleFactory({
        form: verbFormFactory({ shape: 'V-foo' })
      })
    });

    expect(queryByText('V-foo')).to.exist;
  });

  it('presents its translation', function () {
    const { queryByText } = renderExampleCard({
      example: exampleFactory({ translation: 'the translation' })
    });

    expect(queryByText('the translation')).to.exist;
  });

  it('hyperlinks the card', function () {
    const { container } = renderExampleCard({
      example: exampleFactory({ url: '/languages/tl/verb-forms/V-bar/examples/foobar' })
    });

    expect(container.firstChild).to.have.tagName('a');
    expect(container.firstChild).to.have.attribute('href', '/languages/tl/verb-forms/V-bar/examples/foobar');
  });
});
