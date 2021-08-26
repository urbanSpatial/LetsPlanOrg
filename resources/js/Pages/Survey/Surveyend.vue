<template>
  <v-stepper-content step="3">
    <v-card
      class="d-flex flex-column"
      style="height: 100%; width:100%; padding-bottom: 100px;"
    >
      <div v-if="!submitted">
        <div id="ff-compose" />
      </div>

      <div
        v-else
        class="flex-grow-1"
      >
        <p>
          Your response has been recorded!
        </p>
        <p>
          Once more of your neighbors take the survey,
          we will publish the Preservation Indexin the
          <router-link to="/explorer">
            Community Explorer
          </router-link>.
          In the meantime, you can learn more about the OurPlan effort
          <router-link to="/">
            here
          </router-link>. Thank you for participating in OurPlan!
        </p>
      </div>

      <v-card-actions>
        <v-btn
          @click="step_current = 2"
        >
          Back
        </v-btn>

        <v-btn
          color="primary"
          @click="submitForm"
        >
          {{ nexttext }}
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-stepper-content>
</template>

<script>
import { mapFields } from 'vuex-map-fields';

export default {
  name: 'Surveyend',
  components: {},
  data() {
    return {};
  },
  computed: {
    nexttext() {
      return this.submitted ? 'next' : 'submit';
    },
    ...mapFields([
      'blockgroup',
      'user',
      'ipaddress',
      'step_current',
      'survey_id',
      'submitted',
    ]),
  },
  watch: {
    blockgroup() { this.updateUser(); },
    user() { this.updateUser(); },
    ipaddress() { this.updateUser(); },
    step_current(step) {
      if (step === '4') {
        window.console.log('trying to fetch results');
        this.$store.dispatch('update_survey_results_async');
      }
    },
  },
  methods: {
    updateUser() {
      let bg = {};
      if (document.querySelector('#Widget1317426661')) {
        bg = {
          location: this.blockgroup,
          user: this.user,
          ipaddress: this.ipaddress,
        };

        document.querySelector('#Widget1317426661').setAttribute('value', JSON.stringify(bg));
      }
    },
    submitForm() {
      let submitted;
      if (!this.submitted) {
        submitted = window.formFacade.submit(
          document.querySelector(`#Publish${this.survey_id}`),
          'root',
        );
        // return from submit is very strange so convert to false if error, something else
        // and true if success (strangely returns false for all good)
        if (typeof (submitted) === 'undefined' || submitted !== false) {
          this.submitted = false;
        } else if (submitted === false) {
          this.submitted = true;
        }
        return null;
      }

      this.step_current = 4;
      return null;
    },
  },
};
</script>
