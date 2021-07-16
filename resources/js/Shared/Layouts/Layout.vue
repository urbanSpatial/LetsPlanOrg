<template>
  <v-app>
    <v-bottom-navigation
      v-model="currentRoute"
      app
      grow
      color="primary"
      @change="handleNavChange"
    >
      <v-btn
        v-for="item in navItems"
        :key="item.route"
        :value="item.route"
        :style="item.route === 'about' ? 'display:none;' : ''"
      >
        <span>{{ item.title }}</span>
        <v-icon>{{ item.icon }}</v-icon>
      </v-btn>
    </v-bottom-navigation>

    <v-main style="overflow: hidden">
      <slot />
    </v-main>
  </v-app>
</template>

<script>
import '@inertiajs/inertia';

export default {
  data() {
    return {
      currentRoute: route().current(),
      navItems: [
        { title: 'Engage!', route: 'engage', icon: 'mdi-flag-checkered' },
        { title: 'Explore', route: 'explore', icon: 'mdi-chart-timeline-variant' },
        { title: 'Survey', route: 'survey', icon: 'mdi-file-question-outline' },
        { title: 'About', route: 'about', icon: 'mdi-information-outline' },
      ],
    };
  },

  methods: {
    handleNavChange() {
      this.$inertia.get(route(this.currentRoute), {}, { replace: true });
    },
  },
};
</script>
