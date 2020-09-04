<div>
    <div wire:ignore id="alglang-map"></div>
</div>

@push('scripts')
<script type="text/javascript">
    class MapState {
        constructor(leaflet) {
            this.leaflet = leaflet;

            this.map = null;
            this.markers = leaflet.layerGroup();
            this.borders = leaflet.layerGroup();
            this.showBorders = false;

            const _this = this;

            const ShowBorderButton = leaflet.Control.extend({
                onAdd(map) {
                    const button = leaflet.DomUtil.create('button');
                    button.innerText = 'Toggle current political boundaries';
                    button.style.cursor = 'pointer';
                    button.classList.add(
                        'bg-gray-200',
                        'hover:bg-gray-100',
                        'shadow-lg',
                        'px-2',
                        'py-1'
                    );

                    leaflet.DomEvent.on(button, {
                        click() {
                            _this.toggleBorders();
                        }
                    });

                    this.button = button;
                    return button;
                },

                onRemove(map) {
                    leaflet.DomEvent.off(this.button);
                }
            });

            this.button = new ShowBorderButton({ position: 'bottomright' });
        }

        addTiles(url, options = {}) {
            this.leaflet.tileLayer(url, options).addTo(this.map);
            return this;
        }

        addBorder(geoJson) {
            this.borders.addLayer(this.leaflet.geoJSON(geoJson, {
                style: {
                    weight: 1,
                    color: '#4A5568',
                    opacity: 0.3,
                    fill: false
                }
            }));

            return this;
        }

        addMarker(position, popupHtml) {
            const marker = this.leaflet.marker(position);
            marker.bindPopup(popupHtml);
            this.markers.addLayer(marker);

            return this;
        }

        bind(selector, options = {}) {
            this.map = this.leaflet.map(selector, options);
            this.markers.addTo(this.map);
            this.button.addTo(this.map);
            return this;
        }

        toggleBorders() {
            this.showBorders = !this.showBorders;

            if (this.showBorders) {
                this.borders.addTo(this.map);
            } else {
                this.borders.remove();
            }
        }

        fitToMarkers() {
            const positions = Object.values(this.markers._layers).map(marker => marker._latlng);
            const bounds = new this.leaflet.latLngBounds(positions);
            this.map.fitBounds(bounds, { maxZoom: 4 });
            return this;
        }
    }

    async function loadJson(url) {
        const response = await fetch(url);
        return await response.json();
    }

    function initializeMap() {
        const center = [46.0, -87.659916];
        console.log('hello');

        window.mapState = new MapState(window.leaflet);
        window.mapState.bind('alglang-map', {
            center,
            zoom: 4,
            minZoom: 3,
            maxBounds: [
                [71.29, -167.12],
                [26.64, -48.57]
            ]
        });

        // Load tiles
        window.mapState.addTiles(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Shaded_Relief/MapServer/tile/{z}/{y}/{x}',
            { attribution: 'Tiles &copy; Esri &mdash; Source: Esri' }
        );

        // Load markers
        @foreach($locations as $location)
        window.mapState.addMarker(
            {!! json_encode($location->position) !!},
            '<a href="{{ $location->url }}">{{ $location->name }}</a>'
        );
        @endforeach
        window.mapState.fitToMarkers();

        // Load borders
        for (const name of ['canada', 'us-states']) {
            loadJson(`/data/${name}.json`).then(json => window.mapState.addBorder(json));
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        initializeMap();
    });
</script>
@endpush
