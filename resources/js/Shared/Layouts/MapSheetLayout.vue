<template>
  <lp-bottom-sheet @new-pane="handleNewPane">
    <mapbox-map
      ref="mapboxmap"
      :tiles="mapTiles"
      @parcel-click="showPopup"
      @source-data-loaded="sourceDataLoaded"
      @parcel-rank-changed="changeParcelRank"
    />
    <parcel-popup
      v-show="isPopupVisible"
      ref="parcelpopup"
      @close="closePopup"
    >
      <parcel-info
        ref="parcelinfo"
      />
    </parcel-popup>

    <template #sheet>
      <slot />
    </template>
  </lp-bottom-sheet>
</template>

<script>
import { mapFields } from 'vuex-map-fields';
import Layout from './Layout.vue';
import LPBottomSheet from './BottomSheetLayout.vue';
import MapboxMap from '../MapboxMap.vue';
import ParcelPopup from '../ParcelPopup.vue';
import ParcelInfo from '../ParcelInfo.vue';

export default {
  components: {
    'lp-bottom-sheet': LPBottomSheet,
    'mapbox-map': MapboxMap,
    'parcel-popup': ParcelPopup,
    'parcel-info': ParcelInfo,
  },

  layout: Layout,

  data() {
    return {
      isPopupVisible: false,
      mapTiles: route().params.pane,
      parcelSourceId: 'urban-areas',
      rankPropertyMap: {
        sales: 'sale_price_adj',
        construction: 'permitsc',
        alteration: 'permitsa',
        zoning: 'zoning',
      },
    };
  },
  computed: {
    ...mapFields([
      'exploreIsExpanded',
    ]),
  },
  mounted: () => {
  },
  methods: {
    handleNewPane(pane) {
      this.mapTiles = pane;
    },

    sourceDataLoaded(event) {
      // we should not mess with the parcel color
      // ranks when other layers have events
      if (event.sourceId !== 'urban-areas') {
        return;
      }
      this.changeParcelRank('sales');
      /* example usage of triggers for tour
      this.triggerPopup();
      this.triggerExpanded();
      setTimeout(this.triggerCollapsed, 3000);
      */
    },
    changeParcelRank(rankType) {
      const features = this.$refs.mapboxmap.map.querySourceFeatures(
        this.parcelSourceId,
        { sourceLayer: this.parcelSourceId },
      );
      if (this.rankPropertyMap[rankType] === undefined) {
        // clear all rank
        features.forEach((feat) => {
          this.$refs.mapboxmap.map.setFeatureState({
            source: 'urban-areas',
            sourceLayer: 'urban-areas',
            id: feat.id,
          }, { rank: 0 });
        });
        return;
      }
      const propertyName = this.rankPropertyMap[rankType];
      if (propertyName === 'sale_price_adj') {
        this.rankProperty(propertyName, features);
      }
    },
    rankProperty(propertyName, features) {
      // use hard coded breaks for now so will not consider min and max
      /* eslint-disable prefer-spread */
      // const featMax = Math.max.apply(Math, features.map((f) => f.properties[propertyName] || 0));
      /* eslint-disable prefer-spread */
      // const featMin = Math.min.apply(Math, features.map((f) => f.properties[propertyName] || 0));
      features.forEach((feat) => {
        const fstate = { rank: 0 };
        // no scaling or ranking since using hard coded sales price breaks
        fstate.rank = feat.properties.sale_price_adj;
        // for non-numeric and 0 assign negative number to color appropriately
        if (!fstate.rank) {
          fstate.rank = -1;
        }
        this.$refs.mapboxmap.map.setFeatureState({
          source: 'urban-areas',
          sourceLayer: 'urban-areas',
          id: feat.id,
        }, fstate);
      });
    },
    showPopup(event) {
      // click off closes everything
      if (this.$refs.parcelpopup.open) {
        this.$refs.parcelpopup.open = false;
        this.$refs.mapboxmap.highlightClear();
      } else {
        this.isPopupVisible = true;
        this.$refs.parcelpopup.setMapboxMap(this.$refs.mapboxmap.map);
        this.$refs.parcelpopup.setCoords(event.coords);
        this.$refs.parcelinfo.fetchParcel(event.properties.parcel_id);
        this.$refs.mapboxmap.highlightSource(event.feature);
      }
    },
    closePopup() {
      this.$refs.mapboxmap.highlightClear();
    },
    // triggers for use with tour
    triggerPopup() {
      const feature = this.$refs.mapboxmap.map.querySourceFeatures('urban-areas', {
        sourceLayer: 'urban-areas',
        filter: ['==', 'parcel_id', '441425'],
      });
      const coordinates = { lat: 39.9534051531393, lng: -75.20876878307995 };
      const parcel = feature[0].properties;
      this.showPopup({ properties: parcel, coords: coordinates, feature: feature[0] });
    },
    triggerExpanded() {
      this.exploreIsExpanded = true;
    },
    triggerCollapsed() {
      this.exploreIsExpanded = false;
    },
  },
};
</script>
