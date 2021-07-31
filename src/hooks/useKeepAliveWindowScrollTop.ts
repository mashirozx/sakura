import { onDeactivated, watch, onActivated } from 'vue'
import { useWindowScroll } from '@vueuse/core'
import { useState } from '@/hooks'

export default function () {
  const { scrollTop, scrollLeft } = (function () {
    const { x, y } = useWindowScroll()
    return { scrollTop: y, scrollLeft: x }
  })()

  const [scrollTopCache, setScrollTopCache] = useState(0)
  const [isScrollTopSet, setIsScrollTopSet] = useState(false)

  watch(scrollTop, (value) => {
    if (!isScrollTopSet.value) return
    setScrollTopCache(value)
  })

  onActivated(() => {
    window.scrollTo({
      top: scrollLeft.value ?? 0,
      behavior: 'smooth',
    })
    // window.scrollTo(scrollLeft.value ?? 0, scrollTopCache.value)
    setIsScrollTopSet(true)
  })

  onDeactivated(() => {
    setIsScrollTopSet(false)
  })
}
