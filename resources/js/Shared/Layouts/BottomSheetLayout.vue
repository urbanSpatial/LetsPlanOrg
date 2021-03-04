<template>
  <div class="lp-bottom-sheet">
    <div class="lp-sheet__main">
      <slot />
    </div>
    <slot name="sheet" />
  </div>
</template>

<style lang="scss" scoped>
  .lp-bottom-sheet {
    height: 100%;
    position: relative;
  }

  .lp-sheet__main {
    bottom: 0;
    left: 0;
    position: absolute;
    right: 0;
    top: 0;
    z-index: 1;
  }
</style>
