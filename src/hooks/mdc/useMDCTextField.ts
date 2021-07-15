import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCTextField } from '@material/textfield'

const useMDCTextField = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
  const textFieldRef: Ref<MDCTextField | null> = ref(null)

  if (elementRef instanceof Element) {
    textFieldRef.value = new MDCTextField(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        textFieldRef.value = new MDCTextField(element)
      }
    })
  }

  onBeforeUnmount(() => {
    textFieldRef.value?.destroy()
  })

  return textFieldRef
}

export default useMDCTextField
