import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCRipple } from '@material/ripple'

const useMDCRipple = <El>(
  elementRef: El extends Element ? Element : Ref<Element | null>,
  unbounded = false
) => {
  const rippleRef: Ref<MDCRipple | null> = ref(null)

  if (elementRef instanceof Element) {
    rippleRef.value = new MDCRipple(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        rippleRef.value = new MDCRipple(element)
        if (unbounded) rippleRef.value.unbounded = true
      }
    })
  }

  onBeforeUnmount(() => {
    rippleRef.value?.destroy()
  })

  return rippleRef
}

export default useMDCRipple
