import Vue from 'vue';
import Vuex from 'vuex';
import { getField, updateField } from 'vuex-map-fields';
import { parse } from 'papaparse';

Vue.use(Vuex);

export default new Vuex.Store({
  strict: true,
  state: {
    // need special behavior for Kindle Fire Silk Browser
    isSilk: false,

    blockgroup: null,
    step_current: 1,
    ipaddress: null,
    user: null,
    survey_id: null,
    survey_google_gid: {
      '1FAIpQLScC5u7-N9Y7FnDbLVYtU4JoD-6EcSH__nwhsuBZqjTW7Su5Dg': '1441370576',
      '1FAIpQLSf1SVRyc5p6PUReApldNZbTmqBaUnju1LhgW1zloeeID3OVRw': '1286301424',
      '1FAIpQLSenWJ_YMi_92OmaHyChEz1zJK9Dy_vIEEi5xB6OE6uIdt-vOQ': '1892223559',
    },
    survey_results: null,
    submitted: false,
    layers: { devIndex: true, preservation: false },

    // from letsplanorg
    exploreIsExpanded: false,
    layersIsExpanded: true,
    exploreCurrentPane: 0,
    tourShown: false,
  },
  mutations: {
    update_survey_results(state, value) {
      // eslint-disable-next-line no-param-reassign
      state.survey_results = value;
    },
    updateField,
    updateLayers(state, layers) {
      // eslint-disable-next-line no-param-reassign
      state.layers = layers;
    },
  },
  getters: {
    getField,
  },
  actions: {
    update_survey_results_async({ state, commit }) {
      // use survey id to get results from the matching sheet (gid) in the Google spreadsheet
      const gid = state.survey_google_gid[state.survey_id];
      const url = `https://docs.google.com/spreadsheets/d/e/2PACX-1vR6y85fl-iBqZNTAHw7af0u3_SsXkgeJiZe7o50a5BDd8cTMULALSLF1_s-CgcECPlhFwcYL3thIBne/pub?gid=${gid}&single=true&output=csv`;
      fetch(url)
        .then((results) => results.text())
        .then((text) => commit('update_survey_results', parse(text, { header: true }).data));
    },
  },
  modules: {
  },
});
