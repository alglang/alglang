<template>
    <div />
</template>

<script>
import gmapsInit from '../utils/gmaps';

export default {
    props: {
        'apiKey': {
            required: true
        }
    },

    async mounted() {
        const google = await gmapsInit(this.apiKey);
        const map = new google.maps.Map(this.$el, {
            center: { lat: 46.0, lng: -87.659916 },
            zoom: 4,
            disableDefaultUI: true
        });
    }
};
</script>
