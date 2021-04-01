<template>
  <lp-bottom-sheet>
    <mapbox-map
      ref="mapboxmap"
      @parcel-click="showPopup"
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
  methods: {
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
