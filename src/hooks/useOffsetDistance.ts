import { ref, Ref, watch, onMounted, onUnmounted } from 'vue'
// I'm not sure if this influent performence?
export default function <El>(elementRef: El extends Element ? Element : Ref<Element | null>) {
  const offset = ref({
    offsetTop: NaN,
    offsetLeft: NaN,
  })

  let timer: number

  onMounted(() => {
    timer = window.setInterval(() => {
      let element: HTMLElement
      if (elementRef instanceof Element) {
        element = elementRef as HTMLElement
      } else {
        if (!elementRef.value) return
        element = elementRef.value as HTMLElement
      }
      offset.value = {
        offsetTop: element.offsetTop,
        offsetLeft: element.offsetLeft,
      }
    }, 100)
  })

  onUnmounted(() => clearInterval(timer))

  return offset
}
