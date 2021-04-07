<template>
  <figure class="figure">
    <figcaption
      class="figure__description"
      :class="descriptionClass || 'text--secondary'"
    >
      {{ name }}
    </figcaption>
    <div
      class="figure__content"
      :class="contentClass"
    >
      {{ value ? value.toString() : '\u2014' }}
    </div>
  </figure>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      required: true,
    },

    value: {
      type: [String, Number],
      default: null,
    },

    contentClass: {
      type: String,
      default: null,
    },

    descriptionClass: {
      type: String,
      default: 'text--secondary',
    },
  },
};
</script>
