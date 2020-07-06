import '../setup';
import { render, fireEvent, waitFor } from '@testing-library/vue';
import { expect } from 'chai';
import Vue from 'vue';

import Map from '../../../resources/js/components/Map';

describe('Map.vue', function () {
  it('renders no locations by default', async function () {
    const { container } = render(Map);

    await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);

    const markers = container.getElementsByClassName('leaflet-marker-icon');
    expect(markers).to.have.lengthOf(0);
  });

  it('renders locations', async function () {
    const props = {
      locations: [
        {
          position: {
            lat: 45,
            lng: 34
          }
        },
        {
          position: {
            lat: 34,
            lng: 75
          }
        }
      ]
    };

    const { container } = render(Map, { props });

    await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);

    const markers = container.getElementsByClassName('leaflet-marker-icon');
    expect(markers).to.have.lengthOf(2);
  });

  describe('when it has a value', function () {
    describe('when the value has a position', function () {
      it('renders the value', async function () {
        const props = {
          value: {
            position: {
              lat: 45,
              lng: 34
            }
          }
        };

        const { container } = render(Map, { props });

        await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);

        const markers = container.getElementsByClassName('leaflet-marker-icon');
        expect(markers).to.have.lengthOf(1);
      });
    });

    describe('when the value has no position', function () {
      it('does not render the value', async function () {
        const props = {
          value: { position: null }
        };

        const { container } = render(Map, { props });

        await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);

        const markers = container.getElementsByClassName('leaflet-marker-icon');
        expect(markers).to.have.lengthOf(0);
      });
    });
  });

  describe('interaction', function () {
    it('emits an input event when right clicked', async function () {
      const { container, emitted } = render(Map);

      await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);

      await fireEvent(container.firstChild, new MouseEvent('contextmenu'));

      expect(emitted().input).to.exist;
      expect(emitted().input[0][0]).to.have.keys('position');
    });

    it('can be used with v-model', async function () {
      const component = Vue.component('foo', {
        components: {
          'alglang-map': Map
        },
        template: `
<div>
  <p data-testid="lat">{{ value.position.lat }}</p>
  <p data-testid="lng">{{ value.position.lng }}</p>
  <p data-testid="foo">{{ value.foo }}</p>
  <alglang-map data-testid="map" v-model="value" />
</div>`,
        data() {
          return {
            value: {
              foo: 'foo',
              position: {
                lat: 0,
                lng: 0
              }
            }
          };
        }
      });

      const { container, getByTestId } = render(component);
      expect(getByTestId('lat')).to.have.text('0');
      expect(getByTestId('lng')).to.have.text('0');
      expect(getByTestId('foo')).to.have.text('foo');

      await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.exist);
      await fireEvent(getByTestId('map'), new MouseEvent('contextmenu'));

      expect(getByTestId('lat')).to.have.text('46.01222384063236');
      expect(getByTestId('lng')).to.have.text('-87.626953125');
      expect(getByTestId('foo')).to.have.text('foo'); // Shouldn't modify other properties
    });
  });
});
