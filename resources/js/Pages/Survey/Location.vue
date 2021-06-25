<template>
  <v-stepper-content step="2">
    <v-card
      class="d-flex flex-column"
      style="height: 100%;"
    >
      <div
        class="flex-grow-1"
        style="height: 100%;"
      >
        <l-map
          ref="locationMap"
          :zoom="zoom"
          :center="center"
          :options="mapOptions"
          style="height: 100%;"
        >
          <l-tile-layer
            :url="url"
            :attribution="attribution"
          />
          <l-geo-json
            :geojson="sprucehill"
            :options="geojsonOptions"
          />
        </l-map>
      </div>

      <v-card-actions>
        <v-btn
          @click="step_current = 1"
        >
          Back
        </v-btn>

        <v-btn
          color="primary"
          @click="step_current = 3"
        >
          Continue
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-stepper-content>
</template>

<script>
import { latLng, DomEvent } from 'leaflet';
import { LMap, LTileLayer, LGeoJson } from 'vue2-leaflet';
import { mapFields } from 'vuex-map-fields';

import sprucehill from '../../../data/sprucehill.json';

export default {
  name: 'Location',
  components: {
    LMap,
    LTileLayer,
    LGeoJson,
  },
  data() {
    return {
      zoom: 13.5,
      center: latLng(39.95124, -75.20741),
      url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
      attribution:
        '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      currentZoom: 13.5,
      currentCenter: latLng(39.95124, -75.20741),
      showParagraph: false,
      mapOptions: {
        zoomSnap: 0.5,
        tap: false,
      },
      geojsonOptions: {
        name: 'sprucegeojson',
        fillColor: '#555',
        fillOpacity: 0.4,
        color: 'white',
        weight: 2,
        onEachFeature: (function (feature, layer) {
          layer.on('click', () => {
            let selected = false;
            if (layer.options.fillColor === 'purple') {
              selected = true;
            }
            // set all to gray (unselected)
            // eslint-disable-next-line no-underscore-dangle
            layer._map.__sprucehillgeo.setStyle({
              fillColor: '#555',
            });
            // toggle off if previously selected
            layer.setStyle({
              fillColor: selected ? '#555' : 'purple',
            });
            if (selected) {
              this.blockgroup = null;
            } else {
              this.blockgroup = layer.feature.properties;
            }

            DomEvent.stopPropagation();
          });
        }).bind(this),
      },
      showMap: true,
      sprucehill,
    };
  },

  computed: {
    ...mapFields([
      'blockgroup',
      'step_current',
    ]),
  },

  watch: {
    step_current(value) {
      if (value === '2') {
        this.$nextTick(() => {
          this.$refs.locationMap.mapObject.invalidateSize();
        });
      }
    },
  },

  mounted() {
    this.$nextTick(() => {
      this.$refs.locationMap.mapObject.eachLayer((d) => {
        if (d instanceof window.L.GeoJSON) {
          // eslint-disable-next-line no-underscore-dangle
          this.$refs.locationMap.mapObject.__sprucehillgeo = d;
        }
      });
      this.$refs.locationMap.mapObject.invalidateSize();
    });
  },

  methods: {
    zoomUpdate(zoom) {
      this.currentZoom = zoom;
    },
    centerUpdate(center) {
      this.currentCenter = center;
    },
  },

};
</script>
