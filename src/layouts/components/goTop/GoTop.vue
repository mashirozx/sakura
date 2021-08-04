<template>
  <div
    class="go-top__container mdc-card mdc-elevation--z4"
    :data-hide="scrollTop < 200"
    :data-move-side="shouldShowDrawer"
    @click="handleGoTopEvent"
  >
    <Ripple>
      <div class="content">
        <div class="icon"><i class="fas fa-arrow-up"></i></div>
        <div class="progress">{{ progress }}%</div>
      </div>
    </Ripple>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, watch, ref } from 'vue'
import { useWindowScroll } from '@vueuse/core'
import { useWindowResize, useResizeObserver } from '@/hooks'
import Ripple from '@/components/ripple/Ripple.vue'

export default defineComponent({
  components: { Ripple },
  props: {
    showDrawer: { type: Boolean, default: false },
  },
  setup(props) {
    const { y } = useWindowScroll()
    const windowSize = useWindowResize()
    // @ts-ignore
    const documentSize = useResizeObserver(document.body)

    const progress = computed(() => {
      const p = Math.floor(
        (y.value / (documentSize.value.height - windowSize.value.innerHeight)) * 100
      )
      return isNaN(p) ? 0 : p
    })
    const shouldShowDrawer = ref(false)
    watch(
      () => props.showDrawer,
      (state) => (shouldShowDrawer.value = state),
      { immediate: true }
    )

    const handleGoTopEvent = () =>
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      })
    return { progress, scrollTop: y, shouldShowDrawer, handleGoTopEvent }
  },
})
</script>

<style lang="scss" scoped>
.go-top__container {
  position: fixed;
  z-index: 1;
  bottom: 24px;
  right: 24px;
  width: 36px;
  height: 36px;
  opacity: 1;
  transition: all 0.5s;
  overflow: hidden;
  user-select: none;
  cursor: pointer;
  .content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
    align-items: center;
    // .icon{}
    .progress {
      font-size: 12px;
    }
  }
  &[data-hide='true'] {
    opacity: 0;
    pointer-events: none;
  }
  &[data-move-side='true'] {
    transform: translate3d(var(--drawer-width, 260px), 0, 0);
  }
}
</style>
