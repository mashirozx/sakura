import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCDialog } from '@material/dialog'

const useMDCDialog = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
  const mdcRef: Ref<MDCDialog | null> = ref(null)

  if (elementRef instanceof Element) {
    mdcRef.value = new MDCDialog(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        mdcRef.value = new MDCDialog(element)
      }
    })
  }

  onBeforeUnmount(() => {
    mdcRef.value?.destroy()
  })

  return mdcRef
}

export default useMDCDialog
