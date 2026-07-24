import * as maplibregl from 'maplibre-gl';
import maplibreWorkerUrl from 'maplibre-gl/dist/maplibre-gl-worker.mjs?worker&url';
import 'maplibre-gl/dist/maplibre-gl.css';

// MapLibre v6 loads its rendering worker from a separate file; without this it
// requests ./maplibre-gl-worker.mjs relative to the bundle, which 404s.
maplibregl.setWorkerUrl(maplibreWorkerUrl);

const cities = [
    {name: 'Chandigarh', lng: 76.7794, lat: 30.7333},
    {name: 'Ludhiana', lng: 75.8573, lat: 30.9010},
    {name: 'Delhi', lng: 77.2090, lat: 28.6139},
    {name: 'Ghaziabad', lng: 77.4538, lat: 28.6692},
    {name: 'Gurugram', lng: 77.0266, lat: 28.4595},
    {name: 'Noida', lng: 77.3910, lat: 28.5355},
    {name: 'Faridabad', lng: 77.3178, lat: 28.4089},
    {name: 'Jaipur', lng: 75.7873, lat: 26.9124},
    {name: 'Bhopal', lng: 77.4126, lat: 23.2599},
    {name: 'Gandhinagar', lng: 72.6369, lat: 23.2156},
    {name: 'Indore', lng: 75.8577, lat: 22.7196},
    {name: 'Rajkot', lng: 70.8022, lat: 22.3039},
    {name: 'Surat', lng: 72.8311, lat: 21.1702},
    {name: 'Nagpur', lng: 79.0882, lat: 21.1458},
    {name: 'Bokaro', lng: 86.1511, lat: 23.6693, publicSector: true},
    {name: 'Ranchi', lng: 85.3096, lat: 23.3441},
    {name: 'Nashik', lng: 73.7898, lat: 19.9975},
    {name: 'Raipur', lng: 81.6296, lat: 21.2514},
    {name: 'Bhilai', lng: 81.3509, lat: 21.1938, publicSector: true},
    {name: 'Durgapur', lng: 87.3119, lat: 23.5204, publicSector: true},
    {name: 'Rourkela', lng: 84.8536, lat: 22.2604, publicSector: true},
    {name: 'Mumbai', lng: 72.8777, lat: 19.0760},
    {name: 'Navi Mumbai', lng: 73.0297, lat: 19.0330},
    {name: 'Hyderabad', lng: 78.4867, lat: 17.3850},
    {name: 'Bhubaneswar', lng: 85.8245, lat: 20.2961},
    {name: 'Visakhapatnam', lng: 83.2185, lat: 17.6868},
    {name: 'Bengaluru', lng: 77.5946, lat: 12.9716},
    {name: 'Chennai', lng: 80.2707, lat: 13.0827},
    {name: 'Coimbatore', lng: 76.9558, lat: 11.0168},
];

function addIndiaStateBoundaries(map) {
    map.addSource('india-states', {
        type: 'geojson',
        data: '/geojson/india-states.geojson?v=2',
    });

    map.addLayer({
        id: 'india-states-fill',
        type: 'fill',
        source: 'india-states',
        paint: {
            'fill-color': '#2563eb',
            'fill-opacity': 0.04,
        },
    });

    map.addLayer({
        id: 'india-states-line',
        type: 'line',
        source: 'india-states',
        paint: {
            'line-color': '#64748b',
            'line-width': 1,
        },
    });
}

function haversineKm(a, b) {
    const toRad = (deg) => deg * Math.PI / 180;
    const dLat = toRad(b.lat - a.lat);
    const dLng = toRad(b.lng - a.lng);
    const h = Math.sin(dLat / 2) ** 2
        + Math.cos(toRad(a.lat)) * Math.cos(toRad(b.lat)) * Math.sin(dLng / 2) ** 2;

    return 2 * 6371 * Math.asin(Math.sqrt(h));
}

function createMeasureTool(map) {
    map.addSource('measure-line', {
        type: 'geojson',
        data: {type: 'FeatureCollection', features: []},
    });

    map.addLayer({
        id: 'measure-line',
        type: 'line',
        source: 'measure-line',
        paint: {
            'line-color': '#dc2626',
            'line-width': 2,
            'line-dasharray': [2, 2],
        },
    });

    const popup = new maplibregl.Popup({closeButton: false, closeOnClick: false, offset: 24});
    let start = null;

    const setLine = (features) => {
        map.getSource('measure-line').setData({type: 'FeatureCollection', features});
    };

    // Right-click on empty map clears the current measurement.
    map.on('contextmenu', (e) => {
        e.originalEvent.preventDefault();
        start = null;
        setLine([]);
        popup.remove();
    });

    return {
        select(city) {
            if (!start || start.name === city.name) {
                start = city;
                setLine([]);
                popup.setLngLat([city.lng, city.lat])
                    .setText(city.name + ' — right-click another city to measure')
                    .addTo(map);

                return;
            }

            const km = Math.round(haversineKm(start, city));

            setLine([{
                type: 'Feature',
                geometry: {
                    type: 'LineString',
                    coordinates: [[start.lng, start.lat], [city.lng, city.lat]],
                },
            }]);
            popup.setLngLat([(start.lng + city.lng) / 2, (start.lat + city.lat) / 2])
                .setText(start.name + ' ↔ ' + city.name + ': ' + km.toLocaleString() + ' km')
                .addTo(map);
            start = null;
        },
    };
}

function addCityMarkers(map, measure) {
    const bounds = new maplibregl.LngLatBounds();

    for (const city of cities) {
        const label = city.publicSector ? city.name + ' (Public Sector)' : city.name;

        const marker = new maplibregl.Marker({color: city.publicSector ? '#ea580c' : '#2563eb', scale: 0.8})
            .setLngLat([city.lng, city.lat])
            .setPopup(new maplibregl.Popup({closeButton: false, offset: 24}).setText(label))
            .addTo(map);

        marker.getElement().addEventListener('contextmenu', (e) => {
            e.preventDefault();
            e.stopPropagation();
            measure.select(city);
        });

        bounds.extend([city.lng, city.lat]);
    }

    map.fitBounds(bounds, {padding: 60, animate: false});
}

function initWorldMap() {
    const container = document.getElementById('worldmap');

    if (!container) {
        return;
    }

    const loading = document.getElementById('worldmap-loading');

    const map = new maplibregl.Map({
        container,
        style: 'https://demotiles.maplibre.org/style.json',
        center: [0, 20],
        zoom: 1.6,
        minZoom: 1,
        maxPitch: 0,
        pitchWithRotate: false,
        attributionControl: {compact: false},
    });

    // Keep the map flat: no rotation/tilt, and force mercator even if the
    // style ever requests the globe projection.
    map.dragRotate.disable();
    map.touchZoomRotate.disableRotation();
    map.on('style.load', () => {
        map.setProjection({type: 'mercator'});
    });

    map.addControl(new maplibregl.NavigationControl({showCompass: false}), 'top-right');
    map.addControl(new maplibregl.FullscreenControl(), 'top-right');
    map.addControl(new maplibregl.ScaleControl({maxWidth: 120}), 'bottom-left');

    map.on('load', () => {
        loading.style.opacity = '0';
        loading.style.pointerEvents = 'none';
        map.resize();
        addIndiaStateBoundaries(map);
        addCityMarkers(map, createMeasureTool(map));
    });

    map.on('error', (e) => {
        const message = e?.error?.message ?? 'unknown error';
        console.error('WorldMap:', e);
        loading.textContent = 'Map error: ' + message;
        loading.style.opacity = '1';
    });

    const coords = document.getElementById('worldmap-coords');
    map.on('mousemove', (e) => {
        coords.textContent = e.lngLat.lat.toFixed(4) + '°, ' + e.lngLat.lng.toFixed(4) + '°';
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWorldMap);
} else {
    initWorldMap();
}
