<template>
  <div id="map-container" />
</template>

<script>
import mapboxgl from 'mapbox-gl';

export default {
  name: 'MapboxMap',

  props: {
    tiles: {
      type: String,
      default: 'sales',
    },
  },

  data() {
    return {
      map: null,
      colorNone: '#c0c0c0',
      colorRankSteps: [
        'step',
        ['feature-state', 'rank'],
        '#c0c0c0', 1,
        '#28caf4', 20,
        '#377bf4', 40,
        '#311df4', 60,
        '#8104f4', 80,
        '#c804f4',
      ],
      colorZoningCategories: [
        'match',
        ['get', 'zoning'],
        '', '#c0c0c0',
        'residential', '#377bf4',
        'residential-high', '#311df4',
        'commercial', '#8104f4',
        'commercial-high', '#c804f4',
        'industrial', '#28caf4',
        '#c0c0c0',
      ],
      colorPermitsAlterSteps: [
        'step',
        ['get', 'alter_permits'],
        '#c0c0c0', 0,
        '#c0c0c0', 1,
        '#8104f4', 2,
        '#28caf4', 3,
        '#28caf4',
      ],
      colorPermitsConstSteps: [
        'step',
        ['get', 'const_permits'],
        '#c0c0c0', 0,
        '#c0c0c0', 1,
        '#8104f4', 2,
        '#28caf4', 3,
        '#28caf4'
      ],
    };
  },

  watch: {
    // eslint-disable-next-line no-unused-vars
    tiles(newTiles, oldTiles) {
      this.$emit('parcel-rank-changed', newTiles);
      if (newTiles === 'sales') {
        this.map.setPaintProperty('urban-areas-fill', 'fill-color', this.colorRankSteps);
        return;
      }
      if (newTiles === 'zoning') {
        this.map.setPaintProperty('urban-areas-fill', 'fill-color', this.colorZoningCategories);
        return;
      }
      if (newTiles === 'alteration') {
        this.map.setPaintProperty('urban-areas-fill', 'fill-color', this.colorPermitsAlterSteps);
        return;
      }
      if (newTiles === 'construction') {
        this.map.setPaintProperty('urban-areas-fill', 'fill-color', this.colorPermitsConstSteps);
        return;
      }
      this.map.setPaintProperty('urban-areas-fill', 'fill-color', this.colorNone);
    },
  },

  mounted() {
    mapboxgl.accessToken = 'pk.eyJ1IjoibGV0c3BsYW4iLCJhIjoiY2trY3phaHMxMDVnZzJubzV1cjdkOGZhaSJ9.cnIlJqWhotB0yniunP2Z6w';

    // push to end of call stack to avoid layout race
    setTimeout(this.initMap, 0);
  },

  methods: {
    highlightSource(feature) {
      const clickHighlightSource = this.map.getSource('click-highlight');
      clickHighlightSource.setData(feature);
    },
    highlightClear() {
      const clickHighlightSource = this.map.getSource('click-highlight');
      const emptyPolygonFeature = {
        type: 'Feature',
        geometry: {
          type: 'Polygon',
          coordinates: [],
        },
      };
      clickHighlightSource.setData(emptyPolygonFeature);
    },

    initMap() {
      const emptyPolygonFeature = {
        type: 'Feature',
        geometry: {
          type: 'Polygon',
          coordinates: [],
        },
      };

      this.map = new mapboxgl.Map({
        container: 'map-container',
        style: 'mapbox://styles/mapbox/dark-v10?optimize=true',
        center: [-75.207138, 39.950872],
        zoom: 14,
      });

      this.map
        .addControl(new mapboxgl.NavigationControl({ visalizePitch: true }))
        .addControl(new mapboxgl.GeolocateControl())
        .addControl(new mapboxgl.ScaleControl());

      const { map } = this;
      this.map.on('load', () => {
        // Find the ID of the first symbol layer in the map style
        const { layers } = map.getStyle();
        let targetLayerId;
        for (let i = 0; i < layers.length; i += 1) {
          // if (layers[i].type === 'symbol') {
          if (layers[i].id === 'building') {
            // grab the next layer
            targetLayerId = layers[i + 1].id;
            break;
          }
        }
        map
          .addSource('urban-areas', {
            name: 'urban-areas',
            type: 'vector',
            generateId: true,
            promoteId: 'parcel_id',
            /* eslint-disable prefer-template */
            tiles: [process.env.MIX_MBTILE_URL ? process.env.MIX_MBTILE_URL : window.location.origin + '/urban/{z}/{x}/{y}.pbf'],
            maxzoom: 14, // max zoom compiled into the mbtiles file
          });

        const colorNone = '#c0c0c0';
        map
          .addLayer(
            {
              id: 'urban-areas-fill',
              type: 'fill',
              source: 'urban-areas',
              'source-layer': 'urban-areas',
              minzoom: 7, // min zoom to display
              maxzoom: 22, // max zoom to display
              layout: {},
              paint: {
                'fill-color': colorNone,
                'fill-opacity': 0.4,
              },
            },
            // Insert the layer beneath the first symbol layer.
            targetLayerId,
          );

        // add empty source to highlight clicked parcels
        map.addSource('click-highlight', {
          type: 'geojson',
          data: emptyPolygonFeature,
        });
        map.addLayer({
          id: 'click-highlight',
          type: 'line',
          source: 'click-highlight',
          layout: {},
          paint: {
            'line-color': '#f08',
            'line-width': 3,
          },
        });

        // pass events up to vue
        map.on('sourcedata', (e) => {
          // sourceDataType == 'visibility' for events that aren't loading events
          if (e.isSourceLoaded && !e.sourceDataType) {
            this.$emit('source-data-loaded', e);
          }
        });

        map.on('click', 'urban-areas-fill', (e) => {
          const coordinates = e.lngLat;
          const parcel = e.features[0].properties;
          const feature = e.features[0];
          // Ensure that if the map is zoomed out such that multiple
          // copies of the feature are visible, the popup appears
          // over the copy being pointed to.
          while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
            coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
          }
          this.$emit('parcel-click', { properties: parcel, coords: coordinates, feature });
        });
      });
    },
  },
};
</script>

<style lang="scss">
  #map-container {
    height: 100%;
  }

  .mapboxgl-map {
    font: inherit;
  }

  .mapboxgl-popup {
    font-size: .875rem;
    line-height: (4/3);

    .popup__title {
      font-size: 1rem;

      small {
        font-weight: normal;
      }
    }

    .popup__subtitle {
      font-size: .75rem;
      font-weight: normal;
      margin-bottom: .25rem;
    }

    .figure-group {
      border-top: 1px solid rgba(black, .1);
      display: flex;
      padding: .25rem 0;

      &.-scores {
        display: grid;
        grid-gap: .25rem 1rem;
        grid-template-columns: 1fr 1fr;

        .figure {
          margin: 0;
        }

        .figure__content {
          font-size: 1.25rem;
          font-weight: normal;
          letter-spacing: -.05em;
          line-height: 1.125;
        }
      }
    }

    .figure {
      + .figure {
        margin-left: 1rem;
      }
    }

    .figure__description {
      font-size: .75rem;
    }

    .figure__content {
      font-weight: bold;
    }
  }

  .mapboxgl-popup-content {
    padding: .5rem .75rem;
  }

  .mapboxgl-popup-close-button {
    padding: 0 .375rem;
  }

  // account for collapsed bottom sheet
  .mapboxgl-ctrl-bottom-left,
  .mapboxgl-ctrl-bottom-right {
    bottom: 96px;
  }
</style>
