import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCList } from '@material/list'

const useMDCList = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
  const mdcRef: Ref<MDCList | null> = ref(null)

  if (elementRef instanceof Element) {
    mdcRef.value = MDCList.attachTo(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        mdcRef.value = MDCList.attachTo(element)
      }
    })
  }

  onBeforeUnmount(() => {
    mdcRef.value?.destroy()
  })

  return mdcRef
}

export default useMDCList
