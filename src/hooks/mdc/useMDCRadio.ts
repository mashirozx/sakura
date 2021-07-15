import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCRadio } from '@material/radio'

const useMDCRadio = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
  const mdcRef: Ref<MDCRadio | null> = ref(null)

  if (elementRef instanceof Element) {
    mdcRef.value = new MDCRadio(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        mdcRef.value = new MDCRadio(element)
      }
    })
  }

  onBeforeUnmount(() => {
    mdcRef.value?.destroy()
  })

  return mdcRef
}

export default useMDCRadio
