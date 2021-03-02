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
        zoom: 11,
      });

      this.map
        .addControl(new mapboxgl.NavigationControl({ visalizePitch: true }))
        .addControl(new mapboxgl.GeolocateControl())
        .addControl(new mapboxgl.ScaleControl());
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
