import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import tippy from 'tippy.js'
import 'tippy.js/dist/tippy.css'

const useTippy = <El>(
  elementRef: El extends Element ? Element : Ref<Element | null>,
  optionalProps?: Parameters<typeof tippy>[1]
) => {
  const tippyRef: Ref<ReturnType<typeof tippy> | []> = ref([])

  if (elementRef instanceof Element) {
    tippyRef.value = [tippy(elementRef, optionalProps)]
  } else {
    watch(elementRef, (element) => {
      if (element) {
        tippyRef.value = [tippy(element, optionalProps)]
      }
    })
  }

  onBeforeUnmount(() => {
    tippyRef.value[0]?.destroy()
  })

  return tippyRef.value[0] ?? null
}

export default useTippy
