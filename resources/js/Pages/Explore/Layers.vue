<template>
  <div>
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

export default {

  data() {
    return {
      switches: [
        {
          label: 'Preservation',
          color: 'teal',
          isDialogVisible: false,
          value: true,
          tooltip: `The Preservation Index is built from our community survey <a href="/survey">here</a>.
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
};
</script>
