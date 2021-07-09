import { ref, Ref } from 'vue'
export function useElementRef(): [Ref<Element | null>, (element: Element) => void] {
  const elementRef: Ref<Element | null> = ref(null)
  const setElementRef = (element: Element) => {
    if (element) elementRef.value = element
  }
  return [elementRef, setElementRef]
}

// TODO: maybe a getter function as arg?
export function useElementRefs(): [Ref<(Element | null)[]>, (element: Element) => void] {
  const elementRefs: Ref<Array<Element | null>> = ref([])
  const setElementRefs = (element: Element) => {
    if (element) {
      elementRefs.value = Array.prototype.slice.call(element.children)
    }
  }
  return [elementRefs, setElementRefs]
}
