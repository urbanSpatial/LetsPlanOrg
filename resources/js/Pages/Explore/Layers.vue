<template>
  <div>
    <map-legend
      v-if="legendPips"
      class="mb-4"
      :pips="legendPips"
    />
    <div
      v-for="item in switches"
      :key="item.label"
      class="d-flex align-baseline"
    >
      <v-switch
        :key="item.label"
        v-model="item.value"
        class="flex-grow-1"
        :label="item.label"
        :color="item.color"
        :disabled="item.disabled"
        @change="onChange"
      />

      <v-dialog v-model="item.isDialogVisible">
        <template #activator="{ on, attrs }">
          <v-btn
            icon
            v-bind="attrs"
            v-on="on"
          >
            <v-icon>mdi-help-circle-outline</v-icon>
          </v-btn>
        </template>

        <v-card>
          <v-card-title>
            {{ item.label }}
          </v-card-title>

          <!-- eslint-disable-next-line vue/no-v-html -->
          <v-card-text v-html="item.tooltip" />

          <v-card-actions>
            <v-spacer />
            <v-btn
              color="primary"
              text
              @click="item.isDialogVisible = false"
            >
              OK
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
import MapLegend from '../../Shared/MapLegend.vue';

export default {
  components: { MapLegend },
  data() {
    return {
      switches: [
        {
          label: 'Preservation',
          attribute: 'presIndex',
          color: 'teal',
          isDialogVisible: false,
          value: false,
          // disabled: true,
          tooltip: `The Preservation Index is built from our community survey <a href="/sprucehill/survey">here</a>.
            Go to the <a href="https://github.com/urbanSpatial/OurPlan_Methods/blob/main/README.md" target="_blank">OurPlan Methodology</a>
            to learn more about how the index is created.
            Once enough responses have been collected, the Index will become active and you can explore
            preservation preferences for each parcel. The index ranges from 1 to 100. A parcel scoring 100
            suggests an overwhelming preference for preservation on behalf of the community.`,
        },
        /*
        {
          label: 'Community Preferences',
          color: 'purple',
          isDialogVisible: false,
          value: true,
        },
        {
          label: 'Variance Probability',
          color: 'orange',
          isDialogVisible: false,
          value: true,
        },
        */
        {
          label: 'Development Suitability',
          attribute: 'devIndex',
          color: 'lime',
          isDialogVisible: false,
          value: true,
          tooltip: `The Development Suitability Index illustrates which properties are more likely to be
            developed without an official change to the zoning code. The index ranges from 1 to 100, where
            100 reflects a parcel that has the greatest potential to be developed. The index calculates how
            much higher a developer could build if they demolished the property and started anew. More
            information can be found <a href="https://github.com/urbanSpatial/OurPlan_Methods/blob/main/README.md" target="_blank">here</a>.`,
        },
      ],
    };
  },
  computed: {
    legendPips() {
      const count = this.switches.filter(({ value }) => value).length;
      if (!count) {
        // neither switch is flipped, do not show legend
        return null;
      }
      const colors = [
        '#28CAF4',
        '#377BF4',
        '#311DF4',
        '#8104F4',
        '#C804F4',
      ];
      return colors.map((color, i) => ({
        color,
        name: i * 20 * count,
      }));
    },
  },
  methods: {
    onChange() {
      const attributes = {};
      this.switches.forEach(({ attribute, value }) => {
        attributes[attribute] = value;
      });
      this.$store.commit('updateLayers', attributes);
    },
  },
};
</script>
