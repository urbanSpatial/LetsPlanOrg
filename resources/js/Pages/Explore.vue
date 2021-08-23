<template>
  <lp-bottom-sheet
    @toggle="handleToggle"
  >
    <template #sheet-collapsed>
      <v-card-title class="px-1">
        <v-btn
          icon
          @click="() => currentPane--"
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
          @click="() => currentPane++"
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

export default {
  components: {
    'lp-bottom-sheet': LPBottomSheet,
  },

  layout: [Layout, MapSheetLayout],

  data() {
    return {
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
    paneCount() {
      return this.panes.length;
    },

    currentPane: {
      get() {
        const { pane } = route().params;
        return this.panes.findIndex((p) => p.route === pane);
      },
      set(value) {
        let index = value;
        if (index < 0) {
          index = this.panes.length - 1;
        } else if (index >= this.panes.length) {
          index = 0;
        }
        this.$inertia.visit(`/explore/${this.panes[index].route}`, { replace: true });
      },
    },

    ...mapFields([
      'exploreIsExpanded',
    ]),
  },
  methods: {
    handleToggle(newState) {
      this.exploreIsExpanded = newState;
    },
  },
};
</script>
