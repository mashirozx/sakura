<template>
  <div class="toggler__container">
    <div
      class="toggler__content"
      :style="{ maxHeight: $props.show ? `${expandContentHeight}px` : '0px' }"
    >
      <div class="content" :ref="setExpandContentRef">
        <slot></slot>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useElementRef, useResizeObserver } from '@/hooks'

export default defineComponent({
  props: { show: Boolean },
  setup() {
    const [expandContentRef, setExpandContentRef] = useElementRef()
    const expandContentSize = useResizeObserver(expandContentRef)
    const expandContentHeight = computed(() =>
      isNaN(expandContentSize.value.height)
        ? 0
        : expandContentSize.value.height + expandContentSize.value.paddingTop
    )

    return { setExpandContentRef, expandContentHeight }
  },
})
</script>

<style lang="scss" scoped>
.toggler__container {
  width: 100%;
  .toggler__content {
    width: 100%;
    max-height: 0;
    transition: max-height 0.3s ease-in-out;
    overflow: hidden;
    > .content {
      width: 100%;
    }
  }
}
</style>
