<template>
    <div />
</template>

<script>
import gmapsInit from '../utils/gmaps';

export default {
    props: {
        apiKey: {
            required: true
        },

        locations: {
            default: () => [
                {
                    content: '<a href="/">Hello</a>',
                    position: { lat: 46.0, lng: -87 }
                }
            ]
        }
    },

    async mounted() {
        const google = await gmapsInit(this.apiKey);

        const map = new google.maps.Map(this.$el, {
            center: { lat: 46.0, lng: -87.659916 },
            zoom: 4,
            disableDefaultUI: true
        });

        const infoWindow = new google.maps.InfoWindow();

        this.locations.forEach(({ content, position }) => {
            const marker = new google.maps.Marker({ map, position });
            marker.addListener('click', () => {
                infoWindow.setPosition(position);
                infoWindow.setContent(content);
                infoWindow.open(map, marker);
            })
        });
    }
};
</script>
