import { ref, onMounted, onUnmounted } from 'vue'
import { debounce } from 'lodash'

export default function () {
  const windowSize = ref({
    innerWidth: window.innerWidth,
    innerHeight: window.innerHeight,
  })

  function update() {
    windowSize.value.innerWidth = window.innerWidth
    windowSize.value.innerHeight = window.innerHeight
  }

  const debounceUpdate = debounce(update, 100)

  onMounted(() => window.addEventListener('resize', debounceUpdate))

  onUnmounted(() => window.removeEventListener('resize', debounceUpdate))

  return windowSize
}
