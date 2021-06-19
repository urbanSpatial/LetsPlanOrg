<template>
  <ol class="legend d-flex ma-0 pa-0">
    <li
      v-for="pip in pips"
      :key="pip.color"
      class="pip"
    >
      <div
        class="pip__color"
        :style="`background-color: ${pip.color}`"
      />
      <div class="pip__name text-right">
        {{ pip.name }}
      </div>
    </li>
  </ol>
</template>

<script>
export default {
  name: 'ChartLegend',
  props: {
    pips: {
      type: Array,
      default: () => [
        { color: '#28CAF4' },
        { color: '#377BF4' },
        { color: '#311DF4' },
        { color: '#8104F4' },
        { color: '#C804F4' },
      ],
    },
  },
};
</script>

<style scoped lang="scss">
  .pip {
    flex: 1 1 0;
    list-style: none;

    + .pip {
      margin-left: 1px;
    }
  }

  .pip__color {
    height: 1rem;
    width: 100%;
  }

  .pip__name {
    font-size: .75rem;
  }
</style>
