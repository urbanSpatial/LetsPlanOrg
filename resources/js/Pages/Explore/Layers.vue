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

          <v-card-text>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit,
            sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
            Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris
            nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat
            nulla pariatur.
          </v-card-text>

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
        },
      ],
    };
  },
};
</script>
