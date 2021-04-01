<template>
  <v-app>
    <v-container
      fill-height
      class="pt-6 text-center flex-column align-center"
    >
      <p class="mb-n1">
        Welcome to
      </p>
      <h1 class="text-h5 primary--text font-weight-bold">
        OurPlan.in/<wbr>sprucehill
      </h1>
      <p>
        Let&rsquo;s plan a more equitable neighborhood&nbsp;together!
      </p>

      <div class="d-flex flex-column align-stretch">
        <v-card
          elevation="1"
          max-width="300"
          class="mb-3 light-blue lighten-5 rounded-lg"
        >
          <v-card-title class="justify-center pb-0">
            Residents
          </v-card-title>
          <v-card-text>
            <p>
              Have your voice be&nbsp;heard!
            </p>
            <v-btn
              color="primary"
              rounded
              block
              @click="goToSurvey()"
            >
              <v-icon
                left
                dense
              >
                mdi-bullhorn
              </v-icon>
              Take our survey
            </v-btn>
          </v-card-text>
        </v-card>

        <v-card
          elevation="1"
          max-width="300"
          class="purple lighten-5 rounded-lg"
        >
          <v-card-title class="justify-center pb-0">
            Everyone
          </v-card-title>
          <v-card-text>
            <p>Check out the planning analytics and survey&nbsp;results</p>
            <v-btn
              color="purple"
              class="white--text"
              rounded
              block
              @click="goToData()"
            >
              <v-icon
                left
                dense
              >
                mdi-map-search
              </v-icon>
              View the data
            </v-btn>
          </v-card-text>
        </v-card>
      </div>

      <div>
        <v-btn
          text
          rounded
          outlined
          class="mt-6"
          color="primary"
          @click.stop="share"
        >
          <v-icon
            left
            dense
          >
            mdi-share
          </v-icon>
          Tell a friend
        </v-btn>
      </div>

      <v-dialog
        v-model="showShareDialog"
        max-width="375"
      >
        <v-card>
          <v-card-title>Tell a friend</v-card-title>
          <v-card-text>
            Just share this link:
            <v-text-field
              id="link-field"
              class="mt-0 pt-0"
              :value="currentUrl"
              :hint="copyIsConfirmed ? 'Copied!' : null"
              persistent-hint
              readonly
              @click="linkFieldClick"
            />
          </v-card-text>
          <v-card-actions>
            <v-spacer />
            <v-btn
              text
              color="primary"
              @click="tweetUrl"
            >
              Tweet
            </v-btn>
            <v-btn
              text
              color="primary"
              @click="copyUrl"
            >
              Copy
            </v-btn>
            <v-btn
              text
              color="secondary"
              @click="closeShareDialog"
            >
              Done
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-container>
  </v-app>
</template>

<script>
export default {
  layout: null,

  data() {
    return {
      copyIsConfirmed: false,
      showShareDialog: false,
    };
  },

  computed: {
    currentUrl() {
      return window.location.href;
    },

    linkField() {
      return document.getElementById('link-field');
    },
  },

  methods: {
    closeShareDialog() {
      this.linkField.focus();
      this.linkField.setSelectionRange(0, 0);
      this.showShareDialog = false;
      this.copyIsConfirmed = false;
    },

    copyUrl() {
      this.copyIsConfirmed = false;

      if (navigator.clipboard) {
        this.selectAll(this.linkField);
        navigator.clipboard.writeText(this.currentUrl);
        this.copyIsConfirmed = true;
      }
    },

    goToData() {
      this.$inertia.get(route('explore'), {}, { replace: true });
    },

    goToSurvey() {
      // go to survey
    },

    linkFieldClick() {
      this.selectAll(this.linkField);
      this.copyUrl();
    },

    selectAll(input) {
      if (input.setSelectionRange) {
        input.focus();
        input.setSelectionRange(0, input.value.length);
      }
    },

    share() {
      if (navigator.canShare && navigator.canShare({ url: window.location.href })) {
        // use native share if available
        navigator.share({
          url: window.location.href,
          title: window.title,
        });
      } else {
        // otherwise fall back to dialog with url to copy
        this.showShareDialog = true;
      }
    },

    tweetUrl() {
      window.open(`https://twitter.com/intent/tweet?url=${escape(window.location.href)}`);
    },
  },
};
</script>
