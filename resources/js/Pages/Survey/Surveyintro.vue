<template>
  <v-stepper-content step="1">
    <v-card
      class="d-flex flex-column"
      style="height: 100%;"
    >
      <v-row justify="center">
        <v-col
          cols="12"
          md="8"
        >
          <p>
            Welcome to the neighborhood survey! The goal is to collect community feedback
            on where new development should occur and what it should prioritize. We ask that
            only residents of the neighborhood take this survey and just once.
          </p>
          <p>
            Once enough responses have been collected, the results will be added to the
            OurPlan Map Explorer and participants can explore both preservation and
            development opportunities.
          </p>
          <p>
            First, the survey will ask you to select the Census area in which you live.
            Continuing on, we will ask your opinion on where development should occur.
            Finally, we will show you a series of photos illustrating different land uses
            in the community. Which would you like to see preserved into the future?
            More information on how we process survey data can be found [here](link to methods).
          </p>
        </v-col>
      </v-row>

      <v-card-actions>
        <v-btn
          @click="step_current = 2"
        >
          Start Survey
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-stepper-content>
</template>

<script>
import { mapFields } from 'vuex-map-fields';

export default {
  name: 'Survey',
  components: {},
  computed: {
    ...mapFields([
      'step_current',
    ]),
  },
};
</script>
