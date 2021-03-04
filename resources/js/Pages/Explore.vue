<template>
  <lp-bottom-sheet
    :expanded="isExpanded"
    @toggle="handleToggle"
  >
    <template #sheet-collapsed>
      <v-card-title class="px-1">
        <v-btn icon>
          <v-icon>mdi-chevron-left</v-icon>
        </v-btn>
        <div class="flex-grow-1 mx-1">
          Sale price
        </div>
        <v-btn icon>
          <v-icon>mdi-chevron-right</v-icon>
        </v-btn>
      </v-card-title>
    </template>

    <template #sheet-expanded>
      <v-card-text class="mt-n1 pt-0 pb-5">
        <p>Some graphs and such go here.</p>

        <div style="background-color: #ccc; padding-top: 56.25%;" />
      </v-card-text>
    </template>
  </lp-bottom-sheet>
</template>

<script>
import Layout from '../Shared/Layouts/Layout.vue';
import MapSheetLayout from '../Shared/Layouts/MapSheetLayout.vue';
import LPBottomSheet from '../Shared/LPBottomSheet.vue';
import uiState from '../uiState';

export default {
  components: {
    'lp-bottom-sheet': LPBottomSheet,
  },

  layout: [Layout, MapSheetLayout],

  data() {
    return {
      isExpanded: uiState.exploreIsExpanded,
    };
  },

  methods: {
    handleToggle(newState) {
      uiState.exploreIsExpanded = newState;
    },
  },
};
</script>
