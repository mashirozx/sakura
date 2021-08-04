<template>
  <div class="tooltip__container">
    <div
      :class="[
        'tooltip',
        { 'cooltipz--static': $props.disableAnimation },
        { 'cooltipz--visible': $props.alwaysVisible },
      ]"
      :aria-label="$props.tooltip"
      :data-cooltipz-dir="$props.position"
      :data-cooltipz-size="$props.size"
    >
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    tooltip: { type: String, default: 'Hello world!' },
    position: { type: String, default: 'top' }, // bottom-right bottom...
    size: { type: String, default: 'default' }, // small medium large fit
    disableAnimation: { type: Boolean, default: false },
    alwaysVisible: { type: Boolean, default: false },
  },
})
</script>

<style lang="scss" scoped>
@use 'cooltipz-css/src/cooltipz';
.tooltip__container {
  width: 100%;
  height: 100%;
}

[aria-label][data-cooltipz-dir]::after,
[aria-label][class*='cooltipz']::after {
  font-family: Verdana, Geneva, Tahoma, 'Font Awesome 5 Free', 'Font Awesome 5 Brands',
    var(--cooltipz-fontawesome, Arial), sans-serif;
}
</style>
