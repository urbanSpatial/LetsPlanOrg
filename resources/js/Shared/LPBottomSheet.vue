<template>
  <div class="lp-sheet d-flex">
    <v-card class="d-flex flex-column flex-grow-1">
      <v-btn
        block
        plain
        small
        @click="toggle"
      >
        <v-icon>{{ exploreIsExpanded ? 'mdi-chevron-down' : 'mdi-chevron-up' }}</v-icon>
      </v-btn>
      <v-divider />

      <v-expansion-panels
        style="flex: 1 1 auto; overflow: auto"
        flat
        tile
        :value="exploreIsExpanded ? 0 : false"
      >
        <v-expansion-panel readonly>
          <v-expansion-panel-header
            v-show="hasCollapsedContent"
            class="pa-0"
            hide-actions
          >
            <slot name="sheet-collapsed" />
          </v-expansion-panel-header>
          <v-expansion-panel-content
            eager
            class="pa-0"
          >
            <slot
              name="sheet-expanded"
            />
          </v-expansion-panel-content>
        </v-expansion-panel>
      </v-expansion-panels>
    </v-card>
  </div>
</template>

<script>
import { mapFields } from 'vuex-map-fields';

export default {
  name: 'LPBottomSheet',

  props: {
    expanded: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return { };
  },

  computed: {
    hasCollapsedContent() {
      return !!(this.$slots['sheet-collapsed'] && this.$slots['sheet-collapsed'][0]);
    },

    ...mapFields([
      'exploreIsExpanded',
    ]),
  },

  methods: {
    toggle() {
      this.exploreIsExpanded = !this.exploreIsExpanded;
    },
  },
};
</script>

<style lang="scss" scoped>
  .lp-sheet {
    bottom: 0;
    max-height: 100%;
    max-width: 100%;
    position: absolute;
    width: 425px;
    z-index: 2;
  }

  .v-sheet.v-card {
    border-radius: 1rem 1rem 0 0;
  }

  ::v-deep .v-expansion-panel-content__wrap {
    padding: 0;
  }
</style>
