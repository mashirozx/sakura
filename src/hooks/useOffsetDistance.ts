import { ref, Ref } from 'vue'
import useIntervalWatcher from './useIntervalWatcher'

export default function <El>(
  elementRef: El extends HTMLElement ? HTMLElement : Ref<HTMLElement | null>
) {
  const offset = ref({
    offsetTop: NaN,
    offsetLeft: NaN,
  })

  useIntervalWatcher(() => {
    let element: HTMLElement
    if (elementRef instanceof HTMLElement) {
      element = elementRef
    } else {
      if (!elementRef.value) return
      element = elementRef.value as HTMLElement
    }
    offset.value = {
      offsetTop: element.offsetTop,
      offsetLeft: element.offsetLeft,
    }
  })

  return offset
}
