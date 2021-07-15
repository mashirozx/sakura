import { ref, Ref, watch, onBeforeUnmount } from 'vue'
import { MDCTabBar } from '@material/tab-bar'

const useMDCTabBar = <El>(elementRef: El extends Element ? Element : Ref<Element | null>) => {
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

export default useMDCTabBar
