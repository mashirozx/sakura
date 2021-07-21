import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import PerfectScrollbar from 'perfect-scrollbar'

const usePerfectScrollbar = <El>(
  elementRef: El extends Element ? Element : Ref<Element | null>,
  options: PerfectScrollbar.Options = {}
) => {
  const mdcRef: Ref<PerfectScrollbar | null> = ref(null)

  if (elementRef instanceof Element) {
    mdcRef.value = new PerfectScrollbar(elementRef, options)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        mdcRef.value = new PerfectScrollbar(element, options)
      }
    })
  }

  onBeforeUnmount(() => {
    mdcRef.value?.destroy()
  })

  return mdcRef
}

export default usePerfectScrollbar
