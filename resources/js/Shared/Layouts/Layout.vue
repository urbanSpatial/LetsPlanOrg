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
        { title: 'Layers', route: 'layers', icon: 'mdi-layers' },
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
