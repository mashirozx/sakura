import { onUnmounted, onDeactivated } from 'vue'
import getScrollbarWidth from '@/utils/getScrollbarWidth'
export default function () {
  const removeScrollLock = () => {
    const body = document.querySelector('body')
    // TODO: add a fake scroll bar element
    if (body instanceof HTMLElement) {
      body.style.overflow = 'auto'
      body.style.width = '100%'
    }
  }
  const addScrollLock = () => {
    const body = document.querySelector('body')
    if (body instanceof HTMLElement) {
      body.style.overflow = 'hidden'
      body.style.width = `calc(100% - ${String(getScrollbarWidth())}px)`
    }
  }

  onUnmounted(() => {
    removeScrollLock()
  })

  onDeactivated(() => {
    removeScrollLock()
  })

  return [removeScrollLock, addScrollLock]
}
