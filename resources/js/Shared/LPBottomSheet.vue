<template>
  <div class="lp-sheet">
    <v-card>
      <v-btn
        block
        plain
        small
        @click="toggle"
      >
        <v-icon>{{ isExpanded ? 'mdi-chevron-down' : 'mdi-chevron-up' }}</v-icon>
      </v-btn>
      <v-divider />

      <v-expansion-panels
        flat
        tile
        :value="isExpanded ? 0 : false"
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
export default {
  name: 'LPBottomSheet',

  props: {
    expanded: {
      type: Boolean,
      default: false,
    },
  },

  data() {
    return {
      isExpanded: this.expanded,
    };
  },

  computed: {
    hasCollapsedContent() {
      return !!(this.$slots['sheet-collapsed'] && this.$slots['sheet-collapsed'][0]);
    },
  },

  methods: {
    toggle() {
      this.isExpanded = !this.isExpanded;
      this.$emit('toggle', this.isExpanded);
    },
  },
};
</script>

<style lang="scss" scoped>
  .lp-sheet {
    bottom: 0;
    position: absolute;
    width: 100%;
    z-index: 2;
  }

  .v-sheet.v-card {
    border-radius: 1rem 1rem 0 0;
  }

  ::v-deep .v-expansion-panel-content__wrap {
    padding: 0;
  }
</style>
