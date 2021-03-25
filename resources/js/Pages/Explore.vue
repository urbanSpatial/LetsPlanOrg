<template>
  <lp-bottom-sheet
    :expanded="isExpanded"
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
              v-for="(pane, title) in panes"
              :key="title"
            >
              <div class="text-truncate">
                {{ title }}
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
          v-for="(pane, title) in panes"
          :key="title"
        >
          <v-card-text>
            <component :is="pane" />
          </v-card-text>
        </v-window-item>
      </v-window>
    </template>
  </lp-bottom-sheet>
</template>

<script>
import Layout from '../Shared/Layouts/Layout.vue';
import MapSheetLayout from '../Shared/Layouts/MapSheetLayout.vue';
import LPBottomSheet from '../Shared/LPBottomSheet.vue';

// panes
import Alteration from './Explore/Alteration.vue';
import LandUse from './Explore/LandUse.vue';
import NewConstruction from './Explore/NewConstruction.vue';
import SalePrices from './Explore/SalePrices.vue';
import Zoning from './Explore/Zoning.vue';

import uiState from '../uiState';

export default {
  components: {
    'lp-bottom-sheet': LPBottomSheet,
  },

  layout: [Layout, MapSheetLayout],

  data() {
    return {
      isExpanded: uiState.exploreIsExpanded,
      currentPane: uiState.exploreCurrentPane,
      panes: {
        'Sale Prices': SalePrices,
        Zoning,
        'Land Use': LandUse,
        'New Construction Permits': NewConstruction,
        'Alteration Permits': Alteration,
      },
    };
  },

  computed: {
    paneCount() {
      return Object.keys(this.panes).length;
    },
  },

  methods: {
    handleToggle(newState) {
      uiState.exploreIsExpanded = newState;
    },

    goToNextPane() {
      if (this.currentPane + 1 < this.paneCount) {
        this.currentPane += 1;
      } else {
        this.currentPane = 0;
      }

      uiState.exploreCurrentPane = this.currentPane;
    },

    goToPrevPane() {
      if (this.currentPane > 0) {
        this.currentPane -= 1;
      } else {
        this.currentPane = this.paneCount - 1;
      }

      uiState.exploreCurrentPane = this.currentPane;
    },
  },
};
</script>
