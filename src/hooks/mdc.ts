import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCRipple } from '@material/ripple'
import { MDCDialog } from '@material/dialog'
import { MDCTextField } from '@material/textfield'
import { MDCTabBar } from '@material/tab-bar'

export const useMDCRipple = <El>(
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

export const useMDCDialog = <El>(
  elementRef: El extends Element ? Element : Ref<Element | null>
) => {
  const dialogRef: Ref<MDCDialog | null> = ref(null)

  if (elementRef instanceof Element) {
    dialogRef.value = new MDCDialog(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        dialogRef.value = new MDCDialog(element)
      }
    })
  }

  onBeforeUnmount(() => {
    dialogRef.value?.destroy()
  })

  return dialogRef
}

export const useMDCTextField = <El>(
  elementRef: El extends Element ? Element : Ref<Element | null>
) => {
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

export const useMDCTabBar = <El>(
  elementRef: El extends Element ? Element : Ref<Element | null>
) => {
  const tabBarRef: Ref<MDCTabBar | null> = ref(null)

  if (elementRef instanceof Element) {
    tabBarRef.value = new MDCTabBar(elementRef)
  } else {
    watch(elementRef, (element) => {
      if (element) {
        tabBarRef.value = new MDCTabBar(element)
      }
    })
  }

  onBeforeUnmount(() => {
    tabBarRef.value?.destroy()
  })

  return tabBarRef
}
