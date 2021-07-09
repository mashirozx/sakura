import { ref, Ref, watch, computed, onMounted, onUnmounted } from 'vue'
import { useResizeObserver, useWindowResize, useOffsetDistance } from '@/hooks'
import { throttle } from 'lodash'

enum HandlerKey {
  Top = 'top',
  Bottom = 'bottom',
  Left = 'left',
  Right = 'right',
}

function useReachElementSide(
  elementRef: Ref<Element | null>,
  handlers: { [key in HandlerKey]?: () => void },
  offset = 0
) {
  const elementSize = useResizeObserver(elementRef)
  const elementOffset = useOffsetDistance(elementRef)
  const windowSize = useWindowResize()
  const scroll = ref({
    scrollX: window.scrollX,
    scrollY: window.scrollY,
  })

  const handleWindowScrollEvent = (/*event: Event*/) => {
    const { scrollX, scrollY } = window
    scroll.value = { scrollX, scrollY }
  }
  const _handleWindowScrollEvent = throttle(handleWindowScrollEvent, 100)

  onMounted(() => window.addEventListener('scroll', handleWindowScrollEvent))
  onUnmounted(() => window.removeEventListener('scroll', handleWindowScrollEvent))

  const trigger = computed(() => {
    const { innerWidth, innerHeight } = windowSize.value
    const { width, height } = elementSize.value
    const { offsetTop, offsetLeft } = elementOffset.value
    // console.log(innerWidth, innerHeight, width, height, offsetTop, offsetLeft)

    const res: { [key in HandlerKey]: number } = { top: 0, bottom: 0, left: 0, right: 0 }

    if (handlers.top) res['top'] = Math.max(0, offsetTop + offset)
    if (handlers.bottom) res['bottom'] = Math.max(0, offsetTop + height - innerHeight - offset)
    if (handlers.left) res['left'] = Math.max(0, offsetLeft + offset)
    if (handlers.right) res['right'] = Math.max(0, offsetLeft + width + innerWidth - offset)

    return res
  })

  const throttles = {
    top: handlers.top ? throttle(handlers.top, 500) : () => null,
    bottom: handlers.bottom ? throttle(handlers.bottom, 500) : () => null,
    left: handlers.left ? throttle(handlers.left, 500) : () => null,
    right: handlers.right ? throttle(handlers.right, 500) : () => null,
  }

  watch(scroll, (nv, ov) => {
    // console.log(nv, ov)

    if (handlers.top) {
      if (ov.scrollY > trigger.value.top && nv.scrollY <= trigger.value.top) {
        throttles.top()
      }
    }
    if (handlers.bottom) {
      if (ov.scrollY < trigger.value.bottom && nv.scrollY >= trigger.value.bottom) {
        throttles.bottom()
      }
    }
    if (handlers.left) {
      if (ov.scrollX > trigger.value.left && nv.scrollX <= trigger.value.left) {
        throttles.left()
      }
    }
    if (handlers.right) {
      if (ov.scrollX < trigger.value.right && nv.scrollX >= trigger.value.right) {
        throttles.right()
      }
    }
  })
}

export default useReachElementSide
