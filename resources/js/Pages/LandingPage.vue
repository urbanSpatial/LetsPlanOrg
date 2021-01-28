<template>
  <div>
    <div class="fixed-bottom border-top bg-light p-1">
      <b-nav
        align="center"
        fill
      >
        <b-nav-item
          v-for="item, i in navItems"
          :key="item.title"
          class="text-center"
          :active="i==0"
          :disabled="i!=0"
        >
          <b-icon
            :icon="item.icon"
            font-scale="1.5"
            class="d-block mx-auto"
          />
          <small class="sr-only">{{ item.title }}</small>
        </b-nav-item>
      </b-nav>
    </div>

    <b-container>
      <h1 class="h2 my-3">
        LetsPlan
      </h1>

      <p>
        Welcome to LetsPlan, a new approach to neighborhood planning
        where the NY Giants miraculously go on to win the Super Bowl in 2021.
      </p>
      <p>
        Below, participate in the plan or view maps and data about the community.
        Don’t forget to sign the maling list for updates on plan-related events.
      </p>
    </b-container>
  </div>
</template>

<script>
export default {
  data() {
    return {
      navItems: [
        { title: 'About', icon: 'info-circle-fill' },
        { title: 'Explore', icon: 'bar-chart-fill' },
        { title: 'Layers', icon: 'layers-fill' },
      ],
    };
  },
};
</script>
