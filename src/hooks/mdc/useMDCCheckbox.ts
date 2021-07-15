import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCCheckbox } from '@material/checkbox'

const useMDCCheckbox = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
  const mdcRef: Ref<MDCCheckbox | null> = ref(null)

  if (elementRef instanceof Element) {
    mdcRef.value = new MDCCheckbox(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        mdcRef.value = new MDCCheckbox(element)
      }
    })
  }

  onBeforeUnmount(() => {
    mdcRef.value?.destroy()
  })

  return mdcRef
}

export default useMDCCheckbox
