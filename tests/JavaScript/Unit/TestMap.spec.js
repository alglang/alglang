import '../setup';
import { render, fireEvent, waitFor } from '@testing-library/vue';
import { expect } from 'chai';
import Vue from 'vue';

import Map from '../../../resources/js/components/Map';

const renderMap = async props => {
  const wrapper = render(Map, { props });
  await waitFor(() => expect(wrapper.container.querySelector('.leaflet-layer')).to.exist);
  return {
    getMarkers: () => wrapper.container.getElementsByClassName('leaflet-marker-icon'),
    ...wrapper
  };
};

describe('Map.vue', function () {
  it('renders no locations by default', async function () {
    const { getMarkers } = await renderMap();
    expect(getMarkers()).to.have.lengthOf(0);
  });

  it('renders locations', async function () {
    const { getMarkers } = await renderMap({
      locations: [
        {
          position: { lat: 45, lng: 34 }
        },
        {
          position: { lat: 34, lng: 75 }
        }
      ]
    });

    expect(getMarkers()).to.have.lengthOf(2);
  });

  describe('when it has a value', function () {
    describe('when the value has a position', function () {
      it('renders the value', async function () {
        const { getMarkers } = await renderMap({
          value: {
            position: { lat: 45, lng: 34 }
          }
        });

        expect(getMarkers()).to.have.lengthOf(1);
      });
    });

    describe('when the value has no position', function () {
      it('does not render the value', async function () {
        const { getMarkers } = await renderMap({
          value: { position: null }
        });

        expect(getMarkers).to.have.lengthOf(0);
      });
    });
  });

  describe('interaction', function () {
    it('emits an input event when right clicked', async function () {
      const { container, emitted } = await renderMap();

      await fireEvent(container.firstChild, new MouseEvent('contextmenu'));

      expect(emitted().input).to.exist;
      expect(emitted().input[0][0]).to.have.keys('position');
    });

    it('can be used with v-model', async function () {
      const component = Vue.component('foo', {
        components: { 'alglang-map': Map },
        data() {
          return {
            value: {
              foo: 'foo',
              position: { lat: 0, lng: 0 }
            }
          };
        },
        template: `
<div>
  <p data-testid="lat">{{ value.position.lat }}</p>
  <p data-testid="lng">{{ value.position.lng }}</p>
  <p data-testid="foo">{{ value.foo }}</p>
  <alglang-map data-testid="map" v-model="value" />
</div>`
      });

      const { container, getByTestId } = render(component);
      await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);

      expect(getByTestId('lat')).to.have.text('0');
      expect(getByTestId('lng')).to.have.text('0');
      expect(getByTestId('foo')).to.have.text('foo');

      await fireEvent(getByTestId('map'), new MouseEvent('contextmenu'));

      expect(getByTestId('lat')).to.have.text('46.01222384063236');
      expect(getByTestId('lng')).to.have.text('-87.626953125');
      expect(getByTestId('foo')).to.have.text('foo'); // Shouldn't modify other properties
    });

    it('can show political boundaries', async function () {
      const { queryByTestId, getByLabelText } = await renderMap();
      expect(queryByTestId('political-boundaries')).to.not.exist;

      await fireEvent.click(getByLabelText('Political boundary toggle'));

      expect(queryByTestId('political-boundaries')).to.exist;
    });

    it('can hide political boundaries', async function () {
      const { queryByTestId, getByLabelText } = await renderMap();
      await fireEvent.click(getByLabelText('Political boundary toggle'));
      expect(queryByTestId('political-boundaries')).to.exist;

      await fireEvent.click(getByLabelText('Political boundary toggle'));
      expect(queryByTestId('political-boundaries')).to.not.exist;
    });
  });
});
