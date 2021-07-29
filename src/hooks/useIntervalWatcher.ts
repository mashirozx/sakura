import { onMounted, onUnmounted, onActivated, onDeactivated } from 'vue'
export default function useIntervalWatcher(func: () => void, interval = 100): void {
  let timer = NaN
  const addWatcher = () => {
    if (timer) return
    timer = window.setInterval(func, interval)
  }
  const removeWatcher = () => {
    if (!timer) return
    window.clearInterval(timer)
    timer = NaN
  }

  onMounted(() => addWatcher())
  onActivated(() => addWatcher())
  onUnmounted(() => removeWatcher())
  onDeactivated(() => removeWatcher())
}
