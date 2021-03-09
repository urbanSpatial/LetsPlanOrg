<template>
  <div id="map-container" />
</template>

<script>
import mapboxgl from 'mapbox-gl';

export default {
  name: 'MapboxMap',

  data() {
    return {
      map: null,
    };
  },

  mounted() {
    mapboxgl.accessToken = 'pk.eyJ1IjoibGV0c3BsYW4iLCJhIjoiY2trY3phaHMxMDVnZzJubzV1cjdkOGZhaSJ9.cnIlJqWhotB0yniunP2Z6w';

    // push to end of call stack to avoid layout race
    setTimeout(() => {
      this.map = new mapboxgl.Map({
        container: 'map-container',
        style: 'mapbox://styles/mapbox/dark-v10?optimize=true',
        center: [-75.1637900, 39.9523300],
        zoom: 13,
      });

      this.map
        .addControl(new mapboxgl.NavigationControl({ visalizePitch: true }))
        .addControl(new mapboxgl.GeolocateControl())
        .addControl(new mapboxgl.ScaleControl());

      var map = this.map;
      this.map.on('load', function () {
        // Find the ID of the first symbol layer in the map style
        var layers = map.getStyle().layers;
        console.log(layers);
        var targetLayerId;
        for (var i = 0; i < layers.length; i++) {
          //if (layers[i].type === 'symbol') {
          if (layers[i].id === 'building') {
            // grab the next layer
            targetLayerId = layers[i+1].id;
            break;
          }
        }
        map
          .addSource('urban-areas', {
            'name': 'urban-areas',
            'type': 'vector',
            'tiles': [process.env.MIX_MBTILE_URL],
            'maxzoom': 14, // max zoom compiled into the mbtiles file
          });

        map
          .addLayer(
              {
                'id': 'urban-areas-fill',
                'type': 'fill',
                'source': 'urban-areas',
                'source-layer': 'urban-areas',
                'minzoom': 7,  // min zoom to display
                'maxzoom': 22, // max zoom to display
                'layout': {},
                'paint': {
                'fill-color': '#f08',
                'fill-opacity': 0.4
                }
              },
              // Insert the layer beneath the first symbol layer.
              targetLayerId
          );
      });
    }, 0);
  },
};
</script>

<style lang="scss">
  #map-container {
    height: 100%;
  }

  // account for collapsed bottom sheet
  .mapboxgl-ctrl-bottom-left,
  .mapboxgl-ctrl-bottom-right {
    bottom: 96px;
  }
</style>
