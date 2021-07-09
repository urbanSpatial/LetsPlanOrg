<template>
  <lp-bottom-sheet
    @toggle="handleToggle"
  >
    <template #sheet-collapsed>
      <v-card-title class="px-1">
        <v-btn
          icon
          @click="goToPrevPane"
        >
          <span class="d-sr-only">Previous data</span>
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <div
          class="mx-1 overflow-hidden"
          style="flex: 1 1 0"
        >
          <v-window
            v-model="currentPane"
            vertical
          >
            <v-window-item
              v-for="item in panes"
              :key="item.title"
            >
              <div class="text-truncate">
                {{ item.title }}
              </div>
            </v-window-item>
          </v-window>
        </div>
        <v-btn
          icon
          @click="goToNextPane"
        >
          <span class="d-sr-only">Next data</span>
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-card-title>
    </template>

    <template #sheet-expanded>
      <v-window
        v-model="currentPane"
        class="mt-n6"
      >
        <v-window-item
          v-for="item in panes"
          :key="item.title"
        >
          <v-card-text>
            <component :is="item.component" />
          </v-card-text>
        </v-window-item>
      </v-window>
    </template>
  </lp-bottom-sheet>
</template>

<script>
import { mapFields } from 'vuex-map-fields';

import Layout from '../Shared/Layouts/Layout.vue';
import MapSheetLayout from '../Shared/Layouts/MapSheetLayout.vue';
import LPBottomSheet from '../Shared/LPBottomSheet.vue';

// panes
import Alteration from './Explore/Alteration.vue';
import NewConstruction from './Explore/NewConstruction.vue';
import SalePrices from './Explore/SalePrices.vue';
import Zoning from './Explore/Zoning.vue';
import Layers from './Explore/Layers.vue';

import store from '../store';

export default {
  components: {
    'lp-bottom-sheet': LPBottomSheet,
  },

  layout: [Layout, MapSheetLayout],

  props: {
    pane: {
      type: Number,
      default: store.exploreCurrentPane,
    },
  },

  data() {
    return {
      currentPane: this.pane,
      panes: [{
        title: 'Sale Prices',
        component: SalePrices,
        route: 'sales',
      }, {
        title: 'Zoning',
        component: Zoning,
        route: 'zoning',
      }, {
        title: 'New Construction Permits',
        component: NewConstruction,
        route: 'construction',
      }, {
        title: 'Alteration Permits',
        component: Alteration,
        route: 'alteration',
      }, {
        title: 'Planning Overlays',
        component: Layers,
        route: 'layers',
      }],
    };
  },

  computed: {
    currentPaneRoute() {
      return this.panes[this.currentPane].route;
    },

    paneCount() {
      return this.panes.length;
    },

    ...mapFields([
      'exploreIsExpanded',
    ]),
  },

  methods: {
    handleNewPane() {
      window.history.pushState(null, null, `/explore/${this.currentPaneRoute}`);
      store.exploreCurrentPane = this.currentPane;
      this.$parent.$emit('new-pane', this.currentPaneRoute);
    },

    handleToggle(newState) {
      this.exploreIsExpanded = newState;
    },

    goToNextPane() {
      if (this.currentPane + 1 < this.paneCount) {
        this.currentPane += 1;
      } else {
        this.currentPane = 0;
      }

      this.handleNewPane();
    },

    goToPrevPane() {
      if (this.currentPane > 0) {
        this.currentPane -= 1;
      } else {
        this.currentPane = this.paneCount - 1;
      }

      this.handleNewPane();
    },
  },
};
</script>
