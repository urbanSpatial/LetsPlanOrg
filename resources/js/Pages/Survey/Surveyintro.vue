<template>
  <v-stepper-content step="1">
    <v-card
      class="d-flex flex-column"
      style="height: 100%;"
    >
      <div class="flex-grow-1">
        <p>
          Welcome to the neighborhood survey tool.  The purpose of this tool is to
          gather stakeholder feedback on their vision for the future.  Our goal is to
          collect a representative sample, weighing the responses in favor of those
          who may have been unable or discouraged from participating in community
          planning.
        </p>
        <p>
          Once a representative sample has been collected, plan administrators will
          synthesize the results and add them to the
          <router-link to="/explorer">
            Community Explorer
          </router-link> tool, where
          participants can see the results on a map.
        </p>
        <p>
          Step 1 asks you to select the Census block group in which you live.  Then,
          continue to the survey tool.  We ask you to fill out the survey one time.
          We will anonymously assign a 'hash' or identifier to your session to reduce repeat
          survey takers in the sample.
        </p>
      </div>

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
