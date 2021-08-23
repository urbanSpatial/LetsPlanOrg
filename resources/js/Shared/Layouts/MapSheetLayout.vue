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
      'exploreCurrentPane',
      'shownTour',
    ]),
  },
  mounted() {
    // if already shown before then do not show again
    if (this.shownTour) { return; }
    this.$nextTick(() => {
      const tour = this.$shepherd({
        useModalOverlay: true,
      });
      const {
        triggerPopup,
        triggerExpanded, triggerCollapsed,
        triggerPlanningOverlays, triggerSales,
      } = this;

      tour.addStep({
        text: `
          <p>
            Welcome to the OurPlan Map Explorer.
          </p>
          <p>
            Here you can analyze the local market housing trends, zoning patterns, and changes in
            both new construction and alteration permits.
          </p>
          <p>
            Click below to start the tour.
          </p>
        `,
        cancelIcon: { enabled: true },
        buttons: [
          {
            text: 'Continue',
            action: tour.next,
          },
          {
            text: 'Cancel',
            action: tour.cancel,
          },
        ],
      });

      tour.addStep({
        text: `
          <p>
            Click on the map to view key indicators for any property in the neighborhood.
            Use one finger to pan and two to zoom.
          </p>
        `,
        when: {
          show() {
            triggerPopup();
          },
          hide() {
            // enclose in try catch for local testing
            try {
              triggerPopup();
            // eslint-disable-next-line no-empty
            } catch (e) {

            }
          },
        },
        cancelIcon: { enabled: true },
        buttons: [
          {
            text: 'Continue',
            action: tour.next,
          },
          {
            text: 'Cancel',
            action: tour.cancel,
          },
        ],
      });

      tour.addStep({
        text: `
          <p>
            Use the Data View panel from below.  Use the left and right arrows to map different indicators.
            For each, view the graph to get a more in-depth understanding of the trends.
          </p>
        `,
        when: {
          show() {
            triggerExpanded();
          },
        },
        cancelIcon: { enabled: true },
        buttons: [
          {
            text: 'Continue',
            action: tour.next,
          },
          {
            text: 'Cancel',
            action: tour.cancel,
          },
        ],
      });

      tour.addStep({
        text: `
          <p>
            Use the Planning Overlays interface to see how preservation and development suitability
            balance across the neighborhood.  Toggle one indicator at a time, or turn both on to see
            areas suitable for both preservation and development.
          </p>
        `,
        when: {
          show() {
            triggerPlanningOverlays();
          },
        },
        cancelIcon: { enabled: true },
        buttons: [
          {
            text: 'Continue',
            action: tour.next,
          },
          {
            text: 'Cancel',
            action: tour.cancel,
          },
        ],
      });

      tour.addStep({
        text: `
          <p>
            When you are ready, go explore additional components of OurPlan.  Have fun!
          </p>
        `,
        when: {
          show() {
            triggerCollapsed();
            triggerSales();
            // do this the hard way but I do not think we have any utilities for this
            //  and I would prefer not to add another dependency at this point
            document.querySelector('button[value="engage"]').style.border = '2px solid purple';
            document.querySelector('button[value="survey"]').style.border = '2px solid purple';
          },
          hide() {
            document.querySelector('button[value="engage"]').style.border = '';
            document.querySelector('button[value="survey"]').style.border = '';
          },
          cancel() {
            document.querySelector('button[value="engage"]').style.border = '';
            document.querySelector('button[value="survey"]').style.border = '';
          },
        },
        cancelIcon: { enabled: true },
        buttons: [
          {
            text: 'Start Over',
            action() { return this.show(0); },
          },
          {
            text: 'End',
            action: tour.next,
          },
        ],
      });

      tour.on('complete', () => {
        document.querySelector('button[value="engage"]').style.border = '';
        document.querySelector('button[value="survey"]').style.border = '';
      });
      tour.start();
      // set shownTour to true after show when initially mounted
      this.shownTour = true;
    });
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
        const { dev_index: devIndex } = feat.properties;
        // TODO uncomment this line to see preservation on map
        // const preservation = (devIndex * 4357) % 100
        const preservation = undefined;
        const fstate = {
          rank: feat.properties.sale_price_adj || -1,
          combined_layers: (devIndex || 0) + (preservation || 0),
          preservation: preservation || -1,
          devIndex: devIndex || -1,
        };
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
        this.$refs.parcelinfo.fetchParcel(event.properties);
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
    triggerPlanningOverlays() {
      this.exploreCurrentPane = 4;
    },
    triggerSales() {
      this.exploreCurrentPane = 0;
    },
  },
};
</script>

<style>
  .shepherd-content {
    font-family: Roboto,sans-serif;
  }

  .shepherd-text p {
    padding-bottom: 0.5em;
  }
</style>
