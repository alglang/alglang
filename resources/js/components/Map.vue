<template>
  <l-map
    ref="map"
    :center="center"
    :max-bounds="maxBounds"
    :max-bounds-viscosity="1.0"
    @click.right="handleRightClick"
    @ready="handleMapReady"
  >
    <span data-testid="alglang-map" />
    <l-tile-layer
      url="https://server.arcgisonline.com/ArcGIS/rest/services/World_Shaded_Relief/MapServer/tile/{z}/{y}/{x}"
      attribution="Tiles &copy; Esri &mdash; Source: Esri"
    />

    <div
      v-if="showPoliticalBoundaries"
      data-testid="political-boundaries"
    >
      <l-geo-json
        :geojson="statesData"
        :options-style="geoJsonStyle"
      />
      <l-geo-json
        :geojson="provincesData"
        :options-style="geoJsonStyle"
      />
    </div>

    <l-marker
      v-if="value && value.position"
      :lat-lng="value.position"
    >
      <span data-testid="alglang-map-value-marker" />
    </l-marker>

    <l-marker
      v-for="(location, i) of locations"
      :key="i"
      :lat-lng="location.position"
    >
      <span data-testid="alglang-map-marker" />
      <l-popup>
        <a :href="location.url">
          {{ location.name }}
        </a>
      </l-popup>
    </l-marker>

    <l-control position="bottomright">
      <button
        aria-label="Political boundary toggle"
        class="bg-gray-100 bg-opacity-75 px-2 py-1 shadow-lg hover:bg-opacity-100"
        @click="showPoliticalBoundaries = !showPoliticalBoundaries"
      >
        {{ showPoliticalBoundaries ? 'Hide' : 'Show' }} current political boundaries
      </button>
    </l-control>
  </l-map>
</template>

<script>
import 'leaflet/dist/leaflet.css';
import 'leaflet-defaulticon-compatibility/dist/leaflet-defaulticon-compatibility.webpack.css';

import {
  LControl,
  LGeoJson,
  LMap,
  LTileLayer,
  LMarker,
  LPopup
} from 'vue2-leaflet';
import { LatLngBounds } from 'leaflet';
import 'leaflet-defaulticon-compatibility'; // eslint-disable-line import/no-unresolved
import statesData from '../data/us-states.json';
import provincesData from '../data/canada.json';

export default {
  components: {
    LControl,
    LGeoJson,
    LMap,
    LTileLayer,
    LMarker,
    LPopup
  },

  props: {
    value: {
      type: Object,
      default: () => ({})
    },

    locations: {
      type: Array,
      default: () => []
    }
  },

  data() {
    return {
      map: null,
      zoom: 4,
      center: [46.0, -87.659916],
      maxBounds: [
        [71.29, -167.12],
        [26.64, -48.57]
      ],
      statesData,
      provincesData,
      showPoliticalBoundaries: false,
      geoJsonStyle: {
        weight: 1,
        color: '#4A5568',
        opacity: 0.3,
        fill: false
      }
    };
  },

  methods: {
    handleMapReady() {
      this.map = this.$refs.map;
      this.setZoom();
    },

    setZoom() {
      const positions = this.locations.map(location => location.position);

      if (positions.length > 0) {
        const bounds = new LatLngBounds(positions);
        this.map.fitBounds(bounds);
        this.map.setZoom(Math.min(this.zoom, this.map.mapObject.getZoom()));
      } else {
        this.map.setZoom(this.zoom);
      }
    },

    handleRightClick(e) {
      this.$emit('input', {
        ...this.value,
        position: e.latlng
      });
    }
  }
};
</script>
