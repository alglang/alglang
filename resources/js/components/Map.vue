<template>
    <l-map :zoom="zoom" :center="center">
        <l-tile-layer url="https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png" />
        <l-marker v-for="(location, i) of locations" :key="i" :lat-lng="location.position">
            <l-popup>
                <a :href="location.url">
                    {{ location.name }}
                </a>
            </l-popup>
        </l-marker>
    </l-map>
</template>

<script>
import 'leaflet/dist/leaflet.css';
import 'leaflet-defaulticon-compatibility/dist/leaflet-defaulticon-compatibility.webpack.css';

import { LMap, LTileLayer, LMarker, LPopup } from 'vue2-leaflet';
import 'leaflet-defaulticon-compatibility';

const apiKey = process.env.MIX_GMAPS_API_KEY || '';

export default {
    props: {
        locations: {
            default: () => []
        }
    },

    components: {
        LMap,
        LTileLayer,
        LMarker,
        LPopup
    },

    data() {
        return {
            zoom: 4,
            center: [46.0, -87.659916]
        };
    }
};
</script>
