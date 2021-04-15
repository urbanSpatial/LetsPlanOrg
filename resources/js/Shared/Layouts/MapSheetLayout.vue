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

  data() {
    return {
      isPopupVisible: false,
      mapTiles: 'sales',
      parcelSourceId: 'urban-areas',
      rankPropertyMap: {
        'sales': 'sale_price_adj',
        'construction': 'permitsc',
        'alteration': 'permitsa',
        'zoning': 'zoning',
      }
    };
  },
  mounted: () => {
  },
  methods: {
    handleNewPane(pane) {
      console.log('handling new pane:', pane);
      this.mapTiles = pane;
    },

    sourceDataLoaded(event) {
      // we should not mess with the parcel color
      // ranks when other layers have events
      if (event.sourceId !== 'urban-areas') {
        return;
      }
      this.changeParcelRank('sales');
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
            }, {rank: 0});
        });
        return;
      }
      const propertyName = this.rankPropertyMap[rankType];

      /* eslint-disable prefer-spread */
      const featMax = Math.max.apply(Math, features.map((f) => f.properties[propertyName] || 0));
      /* eslint-disable prefer-spread */
      const featMin = Math.min.apply(Math, features.map((f) => f.properties[propertyName]|| 0));
      features.forEach((feat) => {
        const fstate = { rank: 0 };
        if (feat.properties.sale_price_adj) {
          fstate.rank = (feat.properties[propertyName]- featMin) / (featMax - featMin);
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
        this.isPopupVisible = true;
        this.$refs.parcelpopup.setMapboxMap(this.$refs.mapboxmap.map);
        this.$refs.parcelpopup.setCoords(event.coords);
        this.$refs.parcelinfo.fetchParcel(event.properties.parcel_id);
        this.$refs.mapboxmap.highlightSource(event.feature);
      }
    },
  },
};
</script>
