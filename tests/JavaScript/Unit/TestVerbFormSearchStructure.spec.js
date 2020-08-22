import '../setup';
import { fireEvent, render } from '@testing-library/vue';
import { expect } from 'chai';

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

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
      const { getByLabelText } = renderStructure(props);

      expect(getByLabelText('Secondary object')).to.have.length(3);
      expect(getByLabelText('Secondary object')).to.contain.text('None');
      expect(getByLabelText('Secondary object')).to.contain.text('Foo');
      expect(getByLabelText('Secondary object')).to.contain.text('Bar');
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

      await fireEvent.change(getByLabelText('Subject'), {
        target: {
          value: '1s"'
        }
      });

      expect(getByTestId('subject-person')).to.have.value('1');
      expect(getByTestId('subject-number')).to.have.value('1');
      expect(getByTestId('subject-obviative-code')).to.have.value('2');
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

      await fireEvent.change(getByLabelText('Primary object'), {
        target: {
          value: '1s"'
        }
      });

      expect(getByTestId('primary-object-person')).to.have.value('1');
      expect(getByTestId('primary-object-number')).to.have.value('1');
      expect(getByTestId('primary-object-obviative-code')).to.have.value('2');
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

      await fireEvent.change(getByLabelText('Primary object'), {
        target: {
          value: '1s"'
        }
      });
      await fireEvent.change(getByLabelText('Primary object'), {
        target: {
          value: ''
        }
      });

      expect(queryByTestId('primary-object-disabled')).to.exist;
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

      await fireEvent.change(getByLabelText('Secondary object'), {
        target: {
          value: '1s"'
        }
      });

      expect(getByTestId('secondary-object-person')).to.have.value('1');
      expect(getByTestId('secondary-object-number')).to.have.value('1');
      expect(getByTestId('secondary-object-obviative-code')).to.have.value('2');
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
      const { getByLabelText, queryByTestId } = renderStructure(props);

      await fireEvent.change(getByLabelText('Secondary object'), {
        target: {
          value: '1s"'
        }
      });
      await fireEvent.change(getByLabelText('Secondary object'), {
        target: {
          value: ''
        }
      });

      expect(queryByTestId('secondary-object-disabled')).to.exist;
    });
  });
});
