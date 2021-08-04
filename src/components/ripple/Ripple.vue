<template>
  <div class="ripple__container">
    <div class="ripple__content mdc-list-item" :ref="setContainerRef">
      <div class="mdc-list-item__ripple"></div>
      <div class="slot__wrapper">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useMDCRipple, useElementRef } from '@/hooks'

export default defineComponent({
  setup() {
    const [containerRef, setContainerRef] = useElementRef()
    useMDCRipple(containerRef)
    return { setContainerRef }
  },
})
</script>

<style lang="scss" scoped>
.ripple__container {
  width: 100%;
  height: 100%;
  .ripple__content {
    width: 100%;
    height: 100%;
    padding-left: 0;
    padding-right: 0;
    .slot__wrapper {
      width: 100%;
      height: 100%;
    }
  }
}
</style>
