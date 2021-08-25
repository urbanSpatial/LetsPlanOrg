<template>
  <v-stepper
    v-model="step_current"
    non-linear
    class="d-flex flex-column stepper-fill-height"
    style="height:100%;"
  >
    <v-stepper-header
      class="stepper-header"
    >
      <v-stepper-step
        :complete="step_current > 1"
        step="1"
        editable
      >
        Introduction
      </v-stepper-step>

      <v-divider />

      <v-stepper-step
        :complete="step_current > 2"
        step="2"
        editable
        error-icon="$error"
        :rules="[() => blockgroup!==null]"
      >
        Location
      </v-stepper-step>

      <v-divider />

      <v-stepper-step
        :complete="step_current > 3"
        step="3"
        :editable="blockgroup!==null"
      >
        Survey
      </v-stepper-step>

      <v-divider />

      <v-stepper-step
        step="4"
      >
        Summary
      </v-stepper-step>
    </v-stepper-header>

    <v-stepper-items class="d-flex flex-grow-1 flex-shrink-0">
      <Surveyintro />
      <Location />
      <Surveyend />
      <Summary />
    </v-stepper-items>
  </v-stepper>
</template>

<script>
import { mapFields } from 'vuex-map-fields';

import Layout from '../Shared/Layouts/LayoutOverflow.vue';

import Surveyintro from './Survey/Surveyintro.vue';
import Location from './Survey/Location.vue';
import Surveyend from './Survey/Surveyend.vue';
import Summary from './Survey/Summary.vue';

export default {
  name: 'Survey',

  components: {
    Surveyintro,
    Location,
    Surveyend,
    Summary,
  },

  layout: [Layout],

  data() {
    return {
      survey_ids: [
        '1FAIpQLScC5u7-N9Y7FnDbLVYtU4JoD-6EcSH__nwhsuBZqjTW7Su5Dg',
        '1FAIpQLSf1SVRyc5p6PUReApldNZbTmqBaUnju1LhgW1zloeeID3OVRw',
        '1FAIpQLSenWJ_YMi_92OmaHyChEz1zJK9Dy_vIEEi5xB6OE6uIdt-vOQ',
      ],
    };
  },

  computed: {
    ...mapFields([
      'blockgroup',
      'step_current',
      'survey_id',
      'survey_results',
    ]),
  },

  mounted() {
    const surveyScript = document.createElement('script');

    // randomly assign 1 of 3 surveys
    const randIndex = window.Math.floor(window.Math.random() * 3);
    this.survey_id = this.survey_ids[randIndex];

    // async defer
    surveyScript.setAttribute('src', `https://formfacade.com/include/112462077823067593328/form/${this.survey_id}/classic.js?div=ff-compose`);
    document.head.appendChild(surveyScript);
  },
};
</script>

<style scoped>
  .stepper-fill-height {
    overflow: visible;
  }

  .stepper-fill-height >>> .v-stepper__wrapper {
    height: 100%;
  }
  .stepper-header {
    position: -webkit-sticky; position:sticky; top: 0px; z-index: 1099; background-color: white;
  }
</style>

<style>
  .ff-form .ff-image {
    height: 400px !important;
    max-height: 80vh !important;
    object-fit: contain;
  }
  .ff-form .ff-multiple_choice .ff-image {
    display: inline-block !important;
  }
  .ff-form .ff-multiple_choice .ff-check-table {
    display: inline-block !important;
    width: auto !important;
  }
  #ff-id-1317426661 {
    display: none;
  }
  .ff-form .ff-next, .ff-form .ff-submit {
    background-color: #5d33fb !important;
    color: #fff !important;
  }
  .ff-form .ff-item {
    border-bottom: 2px solid lightgray !important;
  }
  .ff-form .ff-item tr {
    height: 1em !important;
  }
  .ff-form .ff-item tr td {
    padding: 0 !important;
  }
  /* hide submit button since we will provide our own */
  .ff-button-bar {
    display: none;
  }
  .ff-powered-img, .ff-powered-img > img {
    display: none !important;
    height: 0;
    width: 0;
  }
  #ff-submit-root {
    width: 100%;
  }
  #ff-title-root {
    display: none !important;
  }
  div.ff-section_header {
    padding-bottom: 0 !important;
  }
  .ff-section_header:not(:first-of-type) {
    padding-top: 3em !important;
  }
  h4.ff-section-header {
    font-size: 150%;
  }
</style>
