import { computed, ref } from 'vue'
import type { Ref } from 'vue'
import useOffsetDistance from './useOffsetDistance'
import useResizeObserver from './useResizeObserver'
import useWindowResize from './useWindowResize'

/**
 * @param elementRef HTMLElement
 * @param trigger the position of element: 'top' | 'bottom' | percentage (0~1)
 * @param to the position of window: 'top' | 'bottom' | percentage (0~1)
 * @returns void
 */
const useScrollToElement = <El>(
  elementRef: El extends HTMLElement ? HTMLElement : Ref<HTMLElement | null>,
  trigger: 'top' | 'bottom' | number | string = 'top',
  to: 'top' | 'bottom' | number | string = 'top'
) => {
  const offset = useOffsetDistance(elementRef)
  const size = useResizeObserver(elementRef as Ref<Element>)
  const windowSize = useWindowResize()

  const target = computed((): number => {
    if (trigger === 'top') {
      return offset.value.offsetTop
    } else if (trigger === 'bottom') {
      return offset.value.offsetTop + size.value.height + size.value.paddingTop
    } else if (typeof trigger === 'number') {
      return offset.value.offsetTop + size.value.height * trigger + size.value.paddingTop
    } else if (typeof trigger === 'string') {
      return offset.value.offsetTop + Number(trigger.replace('px', '')) + size.value.paddingTop
    } else {
      return NaN
    }
  })

  const transform = computed(() => {
    if (to === 'top') {
      return 0
    } else if (to === 'bottom') {
      return windowSize.value.innerHeight
    } else if (typeof to === 'number') {
      return windowSize.value.innerHeight * to
    } else if (typeof to === 'string') {
      return Number(to.replace('px', ''))
    } else {
      return NaN
    }
  })

  const final = computed((): { offset: number; transform: number } => {
    return { offset: target.value, transform: transform.value }
  })

  const pending = ref(false)

  const scrollTrigger = () => {
    pending.value = true
    let timer = window.setInterval(() => {
      if (!isNaN(final.value.offset) && !isNaN(final.value.transform)) {
        if (!pending.value) return
        window.scrollTo({
          top: final.value.offset - final.value.transform,
          behavior: 'smooth',
        })
        pending.value = false
        window.clearInterval(timer)
      }
    }, 100)
  }

  return scrollTrigger
}

export default useScrollToElement
