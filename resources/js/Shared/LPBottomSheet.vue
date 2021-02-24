<template>
  <div class="lp-bottom-sheet">
    <div class="lp-sheet__main">
      <slot />
    </div>
    <div class="lp-sheet">
      <v-card>
        <v-btn
          block
          plain
          small
          @click="isExpanded = !isExpanded"
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
              class="pa-0"
              hide-actions
            >
              <slot name="sheet-collapsed" />
            </v-expansion-panel-header>
            <v-expansion-panel-content class="pa-0">
              <slot
                name="sheet-expanded"
              />
            </v-expansion-panel-content>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-card>
    </div>
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
      isExpanded: true,
    };
  },

  mounted() {
    this.isExpanded = this.expanded;
  },
};
</script>

<style lang="scss" scoped>
  .lp-bottom-sheet {
    height: 100%;
    position: relative;
  }

  .lp-sheet__main {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 1;
  }

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
