import '../setup';
import { fireEvent, render } from '@testing-library/vue';
import { expect } from 'chai';

import {
  featureFactory,
  languageFactory,
  verbClassFactory,
  verbModeFactory,
  verbOrderFactory
} from '../factory';
import VerbFormSearch from '../../../resources/js/components/VerbFormSearch';

const renderVerbFormSearch = props => render(VerbFormSearch, {
  props: {
    languages: [],
    classes: [],
    modes: [],
    orders: [],
    features: [],
    ...props
  }
});

describe('VerbFormSearch.vue', function () {
  describe('option display', function () {
    it('shows its languages', function () {
      const props = {
        languages: [
          languageFactory({ name: 'Foo' }),
          languageFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Languages')).to.have.length(2);
      expect(getByLabelText('Languages')).to.contain.text('Foo');
      expect(getByLabelText('Languages')).to.contain.text('Bar');
    });

    it('shows its classes', function () {
      const props = {
        classes: [
          verbClassFactory({ abv: 'Foo' }),
          verbClassFactory({ abv: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

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
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Order')).to.have.length(2);
      expect(getByLabelText('Order')).to.contain.text('Foo');
      expect(getByLabelText('Order')).to.contain.text('Bar');
    });

    it('shows its modes', function () {
      const props = {
        modes: [
          verbModeFactory({ name: 'Foo' }),
          verbModeFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Mode')).to.have.length(2);
      expect(getByLabelText('Mode')).to.contain.text('Foo');
      expect(getByLabelText('Mode')).to.contain.text('Bar');
    });

    it('shows its subjects', function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Subject')).to.have.length(2);
      expect(getByLabelText('Subject')).to.contain.text('Foo');
      expect(getByLabelText('Subject')).to.contain.text('Bar');
    });

    it('shows its primary objects', function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Primary object')).to.have.length(3);
      expect(getByLabelText('Primary object')).to.contain.text('None');
      expect(getByLabelText('Primary object')).to.contain.text('Foo');
      expect(getByLabelText('Primary object')).to.contain.text('Bar');
    });

    it('shows its secondary objects', function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Secondary object')).to.have.length(3);
      expect(getByLabelText('Secondary object')).to.contain.text('None');
      expect(getByLabelText('Secondary object')).to.contain.text('Foo');
      expect(getByLabelText('Secondary object')).to.contain.text('Bar');
    });
  });

  describe('structure query manipulation', function () {
    it('shows one structure query when it loads', function () {
      const { getAllByLabelText } = renderVerbFormSearch();
      expect(getAllByLabelText('Structure query')).to.have.length(1);
    });

    it('adds a structure query when the add button is pressed', async function () {
      const { getAllByLabelText, getByLabelText } = renderVerbFormSearch();
      await fireEvent.click(getByLabelText('Add structure query'));
      expect(getAllByLabelText('Structure query')).to.have.length(2);
    });

    it('removes a structure query when the minus button is pressed', async function () {
      const { getAllByLabelText, getByLabelText } = renderVerbFormSearch();
      await fireEvent.click(getByLabelText('Add structure query'));
      expect(getAllByLabelText('Structure query')).to.have.length(2);

      await fireEvent.click(getByLabelText('Remove structure query'));
      expect(getAllByLabelText('Structure query')).to.have.length(1);
    });

    it('does not remove a structure query if there is only one query', async function () {
      const { getAllByLabelText, getByLabelText } = renderVerbFormSearch();
      expect(getAllByLabelText('Structure query')).to.have.length(1);

      await fireEvent.click(getByLabelText('Remove structure query'));
      expect(getAllByLabelText('Structure query')).to.have.length(1);
    });
  });
});
