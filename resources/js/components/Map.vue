<template>
    <div />
</template>

<script>
import gmapsInit from '../utils/gmaps';

const apiKey = process.env.MIX_GMAPS_API_KEY || '';

export default {
    props: {
        locations: {
            default: () => [
                {
                    url: '/',
                    name: 'Hello',
                    position: { lat: 46.0, lng: -87 }
                }
            ]
        }
    },

    async mounted() {
        const google = await gmapsInit(apiKey);

        const map = new google.maps.Map(this.$el, {
            center: { lat: 46.0, lng: -87.659916 },
            zoom: 4,
            disableDefaultUI: true
        });

        const infoWindow = new google.maps.InfoWindow();

        this.locations.forEach(({ name, position, url }) => {
            const marker = new google.maps.Marker({ map, position });
            marker.addListener('click', () => {
                infoWindow.setPosition(position);
                infoWindow.setContent(`<a href="${url}">${name}</a>`);
                infoWindow.open(map, marker);
            })
        });

        document.addEventListener('turbolinks:before-cache', () => {
            if (this.$el.firstChild) {
                this.$el.removeChild(this.$el.firstChild);
            }
        });
    }
};
</script>
