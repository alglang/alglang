import { fireEvent, render } from '@testing-library/vue';
import userEvent from '@testing-library/user-event';

import {
  featureFactory,
  verbClassFactory,
  verbModeFactory,
  verbOrderFactory
} from '../factory';
import StructureStructure from '../../../resources/js/components/VerbFormSearchStructure';

const renderStructure = props => render(StructureStructure, {
  props: {
    classes: [],
    modes: [],
    orders: [],
    features: [],
    ...props
  }
});

describe('StructureStructure.vue', function () {
  describe('option display', function () {
    it('shows its classes', function () {
      const props = {
        classes: [
          verbClassFactory({ abv: 'Foo' }),
          verbClassFactory({ abv: 'Bar' })
        ]
      };
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

      expect(getByLabelText('Secondary object').children).toHaveLength(3);
      expect(getByLabelText('Secondary object')).toHaveTextContent('None');
      expect(getByLabelText('Secondary object')).toHaveTextContent('Foo');
      expect(getByLabelText('Secondary object')).toHaveTextContent('Bar');
    });
  });

  describe('entry', function () {
    it('updates its subject parameters', async function () {
      const props = {
        features: [
          featureFactory({ name: 'Foo' }),
          featureFactory({
            name: '1s"',
            person: '1',
            number: 1,
            obviative_code: 2
          })
        ]
      };
      const { getByLabelText, getByTestId } = renderStructure(props);

      await userEvent.selectOptions(getByLabelText('Subject'), '1s"');

      expect(getByTestId('subject-person')).toHaveValue('1');
      expect(getByTestId('subject-number')).toHaveValue('1');
      expect(getByTestId('subject-obviative-code')).toHaveValue('2');
    });

    it('updates its primary object parameters', async function () {
      const props = {
        features: [
          featureFactory({
            name: '1s"',
            person: '1',
            number: 1,
            obviative_code: 2
          })
        ]
      };
      const { getByLabelText, getByTestId } = renderStructure(props);

      await userEvent.selectOptions(getByLabelText('Primary object'), '1s"');

      expect(getByTestId('primary-object-person')).toHaveValue('1');
      expect(getByTestId('primary-object-number')).toHaveValue('1');
      expect(getByTestId('primary-object-obviative-code')).toHaveValue('2');
    });

    it('disables its primary object parameters', async function () {
      const props = {
        features: [
          featureFactory({
            name: '1s"',
            person: '1',
            number: 1,
            obviative_code: 2
          })
        ]
      };
      const { getByLabelText, queryByTestId } = renderStructure(props);

      await userEvent.selectOptions(getByLabelText('Primary object'), '1s"');
      await userEvent.selectOptions(getByLabelText('Primary object'), 'None');

      expect(queryByTestId('primary-object-disabled')).toBeInTheDocument();
    });

    it('updates its secondary object parameters', async function () {
      const props = {
        features: [
          featureFactory({
            name: '1s"',
            person: '1',
            number: 1,
            obviative_code: 2
          })
        ]
      };
      const { getByLabelText, getByTestId } = renderStructure(props);

      await userEvent.selectOptions(getByLabelText('Secondary object'), '1s"');

      expect(getByTestId('secondary-object-person')).toHaveValue('1');
      expect(getByTestId('secondary-object-number')).toHaveValue('1');
      expect(getByTestId('secondary-object-obviative-code')).toHaveValue('2');
    });

    it('disables its secondary object parameters', async function () {
      const props = {
        features: [
          featureFactory({
            name: '1s"',
            person: '1',
            number: 1,
            obviative_code: 2
          })
        ]
      };
      const { getByText, getByLabelText, queryByTestId } = renderStructure(props);

      await userEvent.selectOptions(getByLabelText('Secondary object'), '1s"');
      await userEvent.selectOptions(getByLabelText('Secondary object'), 'None');

      expect(queryByTestId('secondary-object-disabled')).toBeInTheDocument();
    });
  });
});
