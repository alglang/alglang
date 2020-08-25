import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import {
  verbClassFactory,
  verbOrderFactory,
  languageFactory
} from '../factory';
import VerbParadigmSearch from '../../../resources/js/components/VerbParadigmSearch';

const renderVerbParadigmSearch = props => render(VerbParadigmSearch, {
  props: {
    languages: [],
    classes: [],
    orders: [],
    ...props
  }
});

describe('VerbParadigmSearch.vue', function () {
  describe('option display', function () {
    it('shows its languages', function () {
      const props = {
        languages: [
          languageFactory({ name: 'Foo' }),
          languageFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbParadigmSearch(props);

      expect(getByLabelText('Language')).to.have.length(2);
      expect(getByLabelText('Language')).to.contain.text('Foo');
      expect(getByLabelText('Language')).to.contain.text('Bar');
    });

    it('shows its classes', function () {
      const props = {
        classes: [
          verbClassFactory({ abv: 'Foo' }),
          verbClassFactory({ abv: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbParadigmSearch(props);

      expect(getByLabelText('Class')).to.have.length(2);
      expect(getByLabelText('Class')).to.contain.text('Foo');
      expect(getByLabelText('Class')).to.contain.text('Bar');
    });

    it('shows its orders', function () {
      const props = {
        orders: [
          verbOrderFactory({ name: 'Foo' }),
          verbOrderFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbParadigmSearch(props);

      expect(getByLabelText('Order')).to.have.length(2);
      expect(getByLabelText('Order')).to.contain.text('Foo');
      expect(getByLabelText('Order')).to.contain.text('Bar');
    });
  });
});
