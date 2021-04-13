<template>
  <lp-bottom-sheet>
    <mapbox-map
      ref="mapboxmap"
      @parcel-click="showPopup"
      @source-data-loaded="sourceDataLoaded"
    />
    <parcel-popup
      ref="parcelpopup"
      :showed="true"
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
  mounted: () => {
  },
  methods: {
    sourceDataLoaded(event) {
      // we should not mess with the parcel color
      // ranks when other layers have events
      if (event.sourceId !== 'urban-areas') {
        return;
      }
      const features = this.$refs.mapboxmap.map.querySourceFeatures(
        event.sourceId,
        { sourceLayer: event.sourceId },
      );

      /* eslint-disable prefer-spread */
      const featMax = Math.max.apply(Math, features.map((f) => f.properties.sale_price_adj || 0));
      /* eslint-disable prefer-spread */
      const featMin = Math.min.apply(Math, features.map((f) => f.properties.sale_price_adj || 0));
      features.forEach((feat) => {
        const fstate = { rank: 0 };
        if (feat.properties.sale_price_adj) {
          fstate.rank = (feat.properties.sale_price_adj - featMin) / (featMax - featMin);
        }
        fstate.rank = Math.round(fstate.rank * 100) * 10;
        if (fstate.rank > 100) {
          fstate.rank = 100;
        }
        if (Number.isNaN(fstate.rank)) {
          fstate.rank = 0;
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
        this.$refs.parcelpopup.setMapboxMap(this.$refs.mapboxmap.map);
        this.$refs.parcelpopup.setCoords(event.coords);
        this.$refs.parcelinfo.fetchParcel(event.properties.parcel_id);
        this.$refs.mapboxmap.highlightSource(event.feature);
      }
    },
  },
};
</script>
