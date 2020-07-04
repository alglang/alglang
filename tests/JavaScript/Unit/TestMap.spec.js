import { render, waitFor } from '@testing-library/vue';
import { expect } from 'chai';

import Map from '../../../resources/js/components/Map';

describe('Map.vue', function () {
  it('renders no locations by default', async function () {
    const { container } = render(Map);

    await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.not.be.null);

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

    await waitFor(() => expect(container.querySelector('.leaflet-layer')).to.not.be.null);

    const markers = container.getElementsByClassName('leaflet-marker-icon');
    expect(markers).to.have.lengthOf(2);
  });
});
