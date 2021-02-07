import { render } from '@testing-library/vue';

import { languageFactory, nominalParadigmTypeFactory } from '../factory';
import NominalParadigmSearch from '../../../resources/js/components/NominalParadigmSearch';

const renderNominalParadigmSearch = props => render(NominalParadigmSearch, {
  props: {
    languages: [],
    paradigmTypes: [],
    ...props
  }
});

describe('NominalParadigmSearch.vue', function () {
  describe('option display', function () {
    it('shows its languages', function () {
      const props = {
        languages: [
          languageFactory({ name: 'Foo' }),
          languageFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderNominalParadigmSearch(props);

      expect(getByLabelText('Languages').children).toHaveLength(2);
      expect(getByLabelText('Languages')).toHaveTextContent('Foo');
      expect(getByLabelText('Languages')).toHaveTextContent('Bar');
    });

    it('shows its paradigm types', function () {
      const props = {
        paradigmTypes: [
          nominalParadigmTypeFactory({ name: 'Foo' }),
          nominalParadigmTypeFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderNominalParadigmSearch(props);

      expect(getByLabelText('Paradigms').children).toHaveLength(2);
      expect(getByLabelText('Paradigms')).toHaveTextContent('Foo');
      expect(getByLabelText('Paradigms')).toHaveTextContent('Bar');
    });
  });
});
