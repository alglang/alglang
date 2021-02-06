import { render } from '@testing-library/vue';

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

      expect(getByLabelText('Language').children).toHaveLength(2);
      expect(getByLabelText('Language')).toHaveTextContent('Foo');
      expect(getByLabelText('Language')).toHaveTextContent('Bar');
    });

    it('shows its classes', function () {
      const props = {
        classes: [
          verbClassFactory({ abv: 'Foo' }),
          verbClassFactory({ abv: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbParadigmSearch(props);

      expect(getByLabelText('Class').children).toHaveLength(2);
      expect(getByLabelText('Class')).toHaveTextContent('Foo');
      expect(getByLabelText('Class')).toHaveTextContent('Bar');
    });

    it('shows its orders', function () {
      const props = {
        orders: [
          verbOrderFactory({ name: 'Foo' }),
          verbOrderFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbParadigmSearch(props);

      expect(getByLabelText('Order').children).toHaveLength(2);
      expect(getByLabelText('Order')).toHaveTextContent('Foo');
      expect(getByLabelText('Order')).toHaveTextContent('Bar');
    });
  });
});
