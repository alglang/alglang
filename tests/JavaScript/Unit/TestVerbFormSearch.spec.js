import { fireEvent, render } from '@testing-library/vue';

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

      expect(getByLabelText('Languages').children).toHaveLength(2);
      expect(getByLabelText('Languages')).toHaveTextContent('Foo');
      expect(getByLabelText('Languages')).toHaveTextContent('Bar');
    });

    it('shows its classes', function () {
      const props = {
        classes: [
          verbClassFactory({ abv: 'Foo' }),
          verbClassFactory({ abv: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

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
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Order').children).toHaveLength(2);
      expect(getByLabelText('Order')).toHaveTextContent('Foo');
      expect(getByLabelText('Order')).toHaveTextContent('Bar');
    });

    it('shows its modes', function () {
      const props = {
        modes: [
          verbModeFactory({ name: 'Foo' }),
          verbModeFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Mode').children).toHaveLength(2);
      expect(getByLabelText('Mode')).toHaveTextContent('Foo');
      expect(getByLabelText('Mode')).toHaveTextContent('Bar');
    });

    it('shows its subjects', function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Subject').children).toHaveLength(2);
      expect(getByLabelText('Subject')).toHaveTextContent('Foo');
      expect(getByLabelText('Subject')).toHaveTextContent('Bar');
    });

    it('shows its primary objects', function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Primary object').children).toHaveLength(3);
      expect(getByLabelText('Primary object')).toHaveTextContent('None');
      expect(getByLabelText('Primary object')).toHaveTextContent('Foo');
      expect(getByLabelText('Primary object')).toHaveTextContent('Bar');
    });

    it('shows its secondary objects', function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({ name: 'Bar' })
        ]
      };
      const { getByLabelText } = renderVerbFormSearch(props);

      expect(getByLabelText('Secondary object').children).toHaveLength(3);
      expect(getByLabelText('Secondary object')).toHaveTextContent('None');
      expect(getByLabelText('Secondary object')).toHaveTextContent('Foo');
      expect(getByLabelText('Secondary object')).toHaveTextContent('Bar');
    });
  });

  describe('structure query manipulation', function () {
    it('shows one structure query when it loads', function () {
      const { getAllByLabelText } = renderVerbFormSearch();
      expect(getAllByLabelText('Structure query')).toHaveLength(1);
    });

    it('adds a structure query when the add button is pressed', async function () {
      const { getAllByLabelText, getByLabelText } = renderVerbFormSearch();
      await fireEvent.click(getByLabelText('Add structure query'));
      expect(getAllByLabelText('Structure query')).toHaveLength(2);
    });

    it('removes a structure query when the minus button is pressed', async function () {
      const { getAllByLabelText, getByLabelText } = renderVerbFormSearch();
      await fireEvent.click(getByLabelText('Add structure query'));
      expect(getAllByLabelText('Structure query')).toHaveLength(2);

      await fireEvent.click(getByLabelText('Remove structure query'));
      expect(getAllByLabelText('Structure query')).toHaveLength(1);
    });

    it('does not remove a structure query if there is only one query', async function () {
      const { getAllByLabelText, getByLabelText } = renderVerbFormSearch();
      expect(getAllByLabelText('Structure query')).toHaveLength(1);

      await fireEvent.click(getByLabelText('Remove structure query'));
      expect(getAllByLabelText('Structure query')).toHaveLength(1);
    });
  });
});
