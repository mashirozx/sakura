import { ref, watch, Ref } from 'vue'
import ResizeObserver from 'resize-observer-polyfill'

export interface UseResizeObserver {
  width: number
  height: number
  paddingTop: number
  paddingLeft: number
  element?: Element
}

// TODO: add method of watch all inputs in one observer, maybe should use a constructor
export default function useResizeObserver<El>(
  elementRef: El extends Element ? Element : Ref<Element | null>
): Ref<UseResizeObserver> {
  const state: Ref<UseResizeObserver> = ref({
    width: NaN,
    height: NaN,
    paddingTop: NaN,
    paddingLeft: NaN,
    element: undefined,
  })

  const ro = new ResizeObserver((entries) => {
    for (const entry of entries) {
      const { left, top, width, height } = entry.contentRect

      state.value = { width, height, paddingTop: top, paddingLeft: left, element: entry.target }
    }
  })

  if (elementRef instanceof Element) {
    ro.observe(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) ro.observe(element)
    })
  }

  return state
}
