import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import GlossField from '../../../resources/js/components/GlossField';
import { glossFactory } from '../factory';

describe('GlossField.vue', function () {
  it('renders glosses', function () {
    const props = {
      value: [
        glossFactory({
          abv: 'G1',
          url: '/glosses/G1'
        })
      ]
    };

    const { getByText } = render(GlossField, { props });

    expect(getByText('G1')).to.have.tagName('a');
    expect(getByText('G1')).to.have.attribute('href', '/glosses/G1');
  });

  it('does not link glosses that do not have a url', function () {
    const props = {
      value: [
        glossFactory({
          abv: 'G1'
        })
      ]
    };

    const { getByText } = render(GlossField, { props });

    expect(getByText('G1')).to.have.tagName('span');
    expect(getByText('G1')).to.not.have.attribute('href');
  });
});
