import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

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

      expect(getByLabelText('Languages')).to.have.length(2);
      expect(getByLabelText('Languages')).to.contain.text('Foo');
      expect(getByLabelText('Languages')).to.contain.text('Bar');
    });

    it('shows its paradigm types', function () {
      const props = {
        paradigmTypes: [
          nominalParadigmTypeFactory({ name: 'Foo' }),
          nominalParadigmTypeFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderNominalParadigmSearch(props);

      expect(getByLabelText('Paradigms')).to.have.length(2);
      expect(getByLabelText('Paradigms')).to.contain.text('Foo');
      expect(getByLabelText('Paradigms')).to.contain.text('Bar');
    });
  });
});
