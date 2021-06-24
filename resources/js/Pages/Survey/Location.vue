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
      sprucehill: { type: 'FeatureCollection', features: [{ type: 'Feature', properties: { GEOID: '421010087021', NAME: 'Block Group 1, Census Tract 87.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.208761, 39.954879999999999], [-75.2086, 39.955636], [-75.208128, 39.957915], [-75.204145, 39.957422], [-75.204933, 39.955180999999999], [-75.205092, 39.954419], [-75.20546, 39.952681999999999], [-75.20558299999999, 39.952056], [-75.20926399999999, 39.952521999999998], [-75.208761, 39.954879999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010078005', NAME: 'Block Group 5, Census Tract 78, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21712099999999, 39.948609999999998], [-75.21292, 39.949065], [-75.211389, 39.947796], [-75.213961, 39.945974], [-75.21516199999999, 39.946978], [-75.21712099999999, 39.948609999999998]]]] } }, { type: 'Feature', properties: { GEOID: '421010088023', NAME: 'Block Group 3, Census Tract 88.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.205092, 39.954419], [-75.204933, 39.955180999999999], [-75.200098, 39.954581999999998], [-75.200525, 39.952695], [-75.200801, 39.951457999999998], [-75.20308299999999, 39.951744999999998], [-75.202828, 39.95297], [-75.204107, 39.953128], [-75.20546, 39.952681999999999], [-75.205092, 39.954419]]]] } }, { type: 'Feature', properties: { GEOID: '421010086021', NAME: 'Block Group 1, Census Tract 86.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.218954, 39.95926], [-75.217002, 39.959015], [-75.214018, 39.958642999999998], [-75.211039, 39.958276], [-75.211677, 39.955239999999999], [-75.214643, 39.955607], [-75.215633, 39.955726], [-75.215471, 39.956489], [-75.219421, 39.956979], [-75.218954, 39.95926]]]] } }, { type: 'Feature', properties: { GEOID: '421010077001', NAME: 'Block Group 1, Census Tract 77, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21292, 39.949065], [-75.209259, 39.949540999999999], [-75.208044, 39.949748], [-75.205759, 39.949832], [-75.20565599999999, 39.948053], [-75.20704599999999, 39.946754], [-75.208387, 39.945651], [-75.21010299999999, 39.946697], [-75.211389, 39.947796], [-75.21292, 39.949065]]]] } }, { type: 'Feature', properties: { GEOID: '421010079001', NAME: 'Block Group 1, Census Tract 79, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.218347, 39.952552999999998], [-75.21634999999999, 39.952301999999999], [-75.21239299999999, 39.951811], [-75.212716, 39.950292], [-75.218676, 39.951032], [-75.218347, 39.952552999999998]]]] } }, { type: 'Feature', properties: { GEOID: '421010087011', NAME: 'Block Group 1, Census Tract 87.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.212716, 39.950292], [-75.21239299999999, 39.951811], [-75.20932599999999, 39.951436], [-75.209259, 39.949540999999999], [-75.21292, 39.949065], [-75.212716, 39.950292]]]] } }, { type: 'Feature', properties: { GEOID: '421010088021', NAME: 'Block Group 1, Census Tract 88.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.20580199999999, 39.950984999999999], [-75.201151, 39.950413999999998], [-75.20096699999999, 39.949861999999999], [-75.205167, 39.948522], [-75.20565599999999, 39.948053], [-75.205759, 39.949832], [-75.20580199999999, 39.950984999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010087012', NAME: 'Block Group 2, Census Tract 87.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.211039, 39.958276], [-75.208128, 39.957915], [-75.2086, 39.955636], [-75.208761, 39.954879999999999], [-75.211677, 39.955239999999999], [-75.211039, 39.958276]]]] } }, { type: 'Feature', properties: { GEOID: '421010369002', NAME: 'Block Group 2, Census Tract 369, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.207838, 39.941286], [-75.20506, 39.943624], [-75.205373, 39.945057999999999], [-75.20704599999999, 39.946754], [-75.20565599999999, 39.948053], [-75.205167, 39.948522], [-75.20096699999999, 39.949861999999999], [-75.199648, 39.949881999999998], [-75.199204, 39.951257], [-75.197243, 39.951021], [-75.19532199999999, 39.950781], [-75.191689, 39.95032], [-75.187231, 39.947176], [-75.191495, 39.943152999999998], [-75.19267599999999, 39.942679], [-75.193985, 39.942153999999998], [-75.200277, 39.943652], [-75.20298, 39.943294], [-75.204582, 39.942352], [-75.205185, 39.941116], [-75.207099, 39.941281], [-75.207838, 39.941286]]]] } }, { type: 'Feature', properties: { GEOID: '421010086012', NAME: 'Block Group 2, Census Tract 86.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21587199999999, 39.954586], [-75.214643, 39.955607], [-75.211677, 39.955239999999999], [-75.21239299999999, 39.951811], [-75.21634999999999, 39.952301999999999], [-75.21587199999999, 39.954586]]]] } }, { type: 'Feature', properties: { GEOID: '421010092002', NAME: 'Block Group 2, Census Tract 92, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.20688899999999, 39.962485], [-75.20236, 39.962859], [-75.202235, 39.961642999999998], [-75.201971, 39.959176], [-75.20627, 39.958822999999998], [-75.20688899999999, 39.962485]]]] } }, { type: 'Feature', properties: { GEOID: '421010086011', NAME: 'Block Group 1, Census Tract 86.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.22179799999999, 39.955318999999999], [-75.21982299999999, 39.955076], [-75.21587199999999, 39.954586], [-75.21634999999999, 39.952301999999999], [-75.218347, 39.952552999999998], [-75.220295, 39.952791999999998], [-75.222279, 39.95304], [-75.22179799999999, 39.955318999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010086022', NAME: 'Block Group 2, Census Tract 86.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21958599999999, 39.956216], [-75.219421, 39.956979], [-75.215471, 39.956489], [-75.215633, 39.955726], [-75.214643, 39.955607], [-75.21587199999999, 39.954586], [-75.21982299999999, 39.955076], [-75.21958599999999, 39.956216]]]] } }, { type: 'Feature', properties: { GEOID: '421010087022', NAME: 'Block Group 2, Census Tract 87.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.20926399999999, 39.952521999999998], [-75.20558299999999, 39.952056], [-75.20580199999999, 39.950984999999999], [-75.205759, 39.949832], [-75.208044, 39.949748], [-75.209259, 39.949540999999999], [-75.20932599999999, 39.951436], [-75.20926399999999, 39.952521999999998]]]] } }, { type: 'Feature', properties: { GEOID: '421010087013', NAME: 'Block Group 3, Census Tract 87.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.211677, 39.955239999999999], [-75.208761, 39.954879999999999], [-75.20926399999999, 39.952521999999998], [-75.20932599999999, 39.951436], [-75.21239299999999, 39.951811], [-75.211677, 39.955239999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010088012', NAME: 'Block Group 2, Census Tract 88.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.19962199999999, 39.95686], [-75.19612099999999, 39.956429], [-75.196603, 39.954149], [-75.200098, 39.954581999999998], [-75.19962199999999, 39.95686]]]] } }, { type: 'Feature', properties: { GEOID: '421010092001', NAME: 'Block Group 1, Census Tract 92, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21306, 39.962576999999999], [-75.21056, 39.962571], [-75.209058, 39.962329], [-75.20688899999999, 39.962485], [-75.20627, 39.958822999999998], [-75.201971, 39.959176], [-75.201824, 39.957826999999998], [-75.201953, 39.957149], [-75.204145, 39.957422], [-75.208128, 39.957915], [-75.211039, 39.958276], [-75.214018, 39.958642999999998], [-75.21306, 39.962576999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010091003', NAME: 'Block Group 3, Census Tract 91, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.202235, 39.961642999999998], [-75.19943599999999, 39.962042], [-75.198844, 39.960637], [-75.198681, 39.959429], [-75.201137, 39.959241999999999], [-75.19962199999999, 39.95686], [-75.201953, 39.957149], [-75.201824, 39.957826999999998], [-75.201971, 39.959176], [-75.202235, 39.961642999999998]]]] } }, { type: 'Feature', properties: { GEOID: '421010091002', NAME: 'Block Group 2, Census Tract 91, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.201137, 39.959241999999999], [-75.198681, 39.959429], [-75.198844, 39.960637], [-75.19691999999999, 39.960802], [-75.194015, 39.961124], [-75.193905, 39.960513999999999], [-75.193789, 39.959880999999999], [-75.193574, 39.958689], [-75.194152, 39.956179999999999], [-75.19612099999999, 39.956429], [-75.19962199999999, 39.95686], [-75.201137, 39.959241999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010079002', NAME: 'Block Group 2, Census Tract 79, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.218833, 39.950272999999999], [-75.218676, 39.951032], [-75.212716, 39.950292], [-75.21292, 39.949065], [-75.21712099999999, 39.948609999999998], [-75.219234, 39.948367999999998], [-75.218833, 39.950272999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010077002', NAME: 'Block Group 2, Census Tract 77, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21010299999999, 39.946697], [-75.208387, 39.945651], [-75.20704599999999, 39.946754], [-75.205373, 39.945057999999999], [-75.20506, 39.943624], [-75.207838, 39.941286], [-75.208799, 39.941644], [-75.207649, 39.942475], [-75.211401, 39.945802], [-75.21010299999999, 39.946697]]]] } }, { type: 'Feature', properties: { GEOID: '421010088022', NAME: 'Block Group 2, Census Tract 88.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.204145, 39.957422], [-75.201953, 39.957149], [-75.19962199999999, 39.95686], [-75.200098, 39.954581999999998], [-75.204933, 39.955180999999999], [-75.204145, 39.957422]]]] } }, { type: 'Feature', properties: { GEOID: '421010074001', NAME: 'Block Group 1, Census Tract 74, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.211401, 39.945802], [-75.207649, 39.942475], [-75.208799, 39.941644], [-75.212687, 39.944885], [-75.211401, 39.945802]]]] } }, { type: 'Feature', properties: { GEOID: '421010088024', NAME: 'Block Group 4, Census Tract 88.02, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.20558299999999, 39.952056], [-75.20546, 39.952681999999999], [-75.204107, 39.953128], [-75.202828, 39.95297], [-75.20308299999999, 39.951744999999998], [-75.200801, 39.951457999999998], [-75.199204, 39.951257], [-75.199648, 39.949881999999998], [-75.20096699999999, 39.949861999999999], [-75.201151, 39.950413999999998], [-75.20580199999999, 39.950984999999999], [-75.20558299999999, 39.952056]]]] } }, { type: 'Feature', properties: { GEOID: '421010105003', NAME: 'Block Group 3, Census Tract 105, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21618099999999, 39.962545999999999], [-75.216376, 39.964093999999999], [-75.213228, 39.964331], [-75.21365, 39.967696], [-75.211231, 39.967878999999999], [-75.21056, 39.962571], [-75.21306, 39.962576999999999], [-75.214018, 39.958642999999998], [-75.217002, 39.959015], [-75.21618099999999, 39.962545999999999]]]] } }, { type: 'Feature', properties: { GEOID: '421010074002', NAME: 'Block Group 2, Census Tract 74, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.212687, 39.944885], [-75.208799, 39.941644], [-75.207838, 39.941286], [-75.21065, 39.939198999999998], [-75.212064, 39.938213], [-75.213865, 39.939974], [-75.21260099999999, 39.94085], [-75.215246, 39.943087], [-75.212687, 39.944885]]]] } }, { type: 'Feature', properties: { GEOID: '421010078001', NAME: 'Block Group 1, Census Tract 78, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.21652, 39.944171], [-75.213961, 39.945974], [-75.211389, 39.947796], [-75.21010299999999, 39.946697], [-75.211401, 39.945802], [-75.212687, 39.944885], [-75.215246, 39.943087], [-75.215615, 39.943402], [-75.21652, 39.944171]]]] } }, { type: 'Feature', properties: { GEOID: '421010088011', NAME: 'Block Group 1, Census Tract 88.01, Philadelphia County, Pennsylvania' }, geometry: { type: 'MultiPolygon', coordinates: [[[[-75.200525, 39.952695], [-75.200098, 39.954581999999998], [-75.196603, 39.954149], [-75.197243, 39.951021], [-75.199204, 39.951257], [-75.200801, 39.951457999999998], [-75.200525, 39.952695]]]] } }] },
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
      if (value === 2) {
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
