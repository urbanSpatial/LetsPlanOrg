<template>
  <v-stepper-content step="4">
    <v-card
      class="mb-12 d-flex flex-column"
      color="grey lighten-1"
      style="height: 100%;"
    >
      <div
        class="flex-grow-1 flex-shrink-0"
        v-html="survey_results === null ? '' : survey_results.map(d => JSON.stringify(d)).join('<br/>')"
      />
      <v-card-actions>
        <v-btn
          @click="step_current = 3"
        >
          Back
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-stepper-content>
</template>

<script>
import { mapFields } from 'vuex-map-fields';

export default {
  name: 'Summary',
  components: {},
  computed: {
    ...mapFields([
      'step_current',
      'survey_results',
    ]),
  },
};
</script>
