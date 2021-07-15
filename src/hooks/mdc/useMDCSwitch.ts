import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCSwitch } from '@material/switch'

const useMDCSwitch = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
  const mdcRef: Ref<MDCSwitch | null> = ref(null)

  if (elementRef instanceof Element) {
    mdcRef.value = new MDCSwitch(elementRef as HTMLButtonElement)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        mdcRef.value = new MDCSwitch(element as HTMLButtonElement)
      }
    })
  }

  onBeforeUnmount(() => {
    mdcRef.value?.destroy()
  })

  return mdcRef
}

export default useMDCSwitch
