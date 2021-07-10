<template>
  <v-container>
    <v-row justify="center">
      <v-col
        cols="12"
        md="8"
        align="center"
      >
        <!-- eslint-disable-next-line vue/html-self-closing -->
        <img
          src="/images/OurPlan_logo.png"
          style="max-height: 150px; max-width: 100%;"
        />
        <h2>
          OurPlan (v0.1) is a new civic tech platform bringing together YIMBYs and NIMBYs
          to plan for more equitable urban neighborhoods.
        </h2>
        <p>
          OurPlan surveys communities to understand their shared preference for preservation
          and development. The OurPlan Mapping Explorer helps identify shared opportunities
          for development.
        </p>
        <p>
          OurPlan is a living plan that can guide neighborhood stakeholders on future
          development decisions.
        </p>
        <p>
          Currently, OurPlan is live in
          <span style="font-weight: bold;">Spruce Hill, Philadelphia</span>.
        </p>
        <h1 class="primary--text">
          Click <a href="/engage">here</a> to check out OurPlan in Spruce Hill!
        </h1>
        <!-- eslint-disable-next-line vue/html-self-closing -->
        <img
          src="/images/OurPlan_city.png"
          style="max-height: 300px; max-width: 100%;"
        />
        <p>
          Watch these short videos to learn why land use and equity is so important.
        </p>
        <h1>...Three Videos Here...</h1>
      </v-col>
    </v-row>
    <v-row justify="center">
      <v-col
        cols="12"
        md="8"
        justify="start"
      >
        <v-divider />
        <h1>About OurPlan</h1>
        <p>
          OurPlan started as a student project in the Master of Urban Spatial Analytics
          program at the University of Pennsylvania, but has since evolved into an open
          platform we hope can help guide development decisions in changing neighborhoods.
          While not fully automated, OurPlan can currently be deployed in any Philadelphia
          neighborhood.
        </p>
        <p>
          For more technical discussion of the motivation, data, and algorithms used in OurPlan,
          check out the OurPlan Methods page
          <a
            href="https://github.com/urbanSpatial/OurPlan_Methods/blob/main/README.md"
            target="_blank"
          >here</a>.
        </p>
        <p>
          We are still discovering who can benefit from OurPlan but we foresee potential users
          including neighborhood groups, the Planning Commission, City Councilpersons, and bespoke
          community engagement endeavors. We’d love to talk to you about how OurPlan can help!
          Reach out
          <a href="mailto:ksteif@upenn.edu">ksteif@upenn.edu</a>
          for more info.
        </p>
        <p>
          The OurPlan team:
        </p>
        <h3>
          Executive Team:
        </h3>
        <p>
          Ken Steif, Akira Drake Rodriguez, Sydney Goldstein
        </p>
        <h3>
          Technology Team:
        </h3>
        <p>
          Kenton Russell, Jarvus Innovations
        </p>
        <h3>
          Art & Illustration:
        </h3>
        <p>
          Jessica Ornelas
        </p>
        <h3>
          With great thanks and respect to the funders of OurPlan:
        </h3>
        <p>
          <!-- eslint-disable-next-line vue/html-self-closing -->
          The Spruce Hill Trust<br />
          The Knight Foundation
        </p>
        <div style="display:flex; justify-content:center;">
          <!-- eslint-disable-next-line vue/html-self-closing -->
          <img
            src="/images/OurPlan_logo.png"
            style="max-height: 150px; max-width: 100%;"
          />
        </div>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import Layout from '../Shared/Layouts/Layout.vue';

export default {
  name: 'About',

  layout: [Layout],

  data() {
    return {
    };
  },
};
</script>
