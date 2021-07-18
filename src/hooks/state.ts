import { ref, readonly, UnwrapRef, DeepReadonly, Ref } from 'vue'
import storage from '@/utils/storage'

// TODO: correct return type??
export const useState = <T>(
  defaultValue: T,
  shouldReadonly = true
): [Ref<UnwrapRef<T>>, (arg: T) => void] => {
  const state = ref(defaultValue)
  const set = (value: T): void => {
    state.value = value as UnwrapRef<T>
  }
  const get = (shouldReadonly ? readonly(state) : state) as Ref<UnwrapRef<T>>
  return [get, set]
}

export const usePersistedState = <K, T>(
  key: K,
  defaultValue: T,
  shouldReadonly = true,
  cachePeriod?: number
): [Ref<UnwrapRef<T>>, (arg: T) => void] => {
  cachePeriod = cachePeriod ?? 24 * 60 * 60
  let state = ref(defaultValue)

  const s = new storage()
  let fetchingStorage = true
  let pendingSet: T

  const set = (value: T): void => {
    if (fetchingStorage) {
      pendingSet = value
      return
    }
    state.value = value as UnwrapRef<T>
    s.set(key, value, cachePeriod)
  }

  s.get(key)
    .then((res) => {
      fetchingStorage = false
      if (pendingSet) {
        set(pendingSet)
      } else if (res) {
        set(res)
      }
    })
    .catch(() => {
      fetchingStorage = false
      if (pendingSet) set(pendingSet)
    })

  const get = (shouldReadonly ? readonly(state) : state) as Ref<UnwrapRef<T>>

  return [get, set]
}
