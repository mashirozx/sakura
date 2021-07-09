import { ref, readonly, UnwrapRef, DeepReadonly, Ref } from 'vue'
import storage from '@/utils/storage'

// TODO: correct return type??
export const useState = <T>(defaultValue: T): any => {
  const state = ref(defaultValue)
  const set = (value: T): void => {
    state.value = value as UnwrapRef<T>
  }
  const get = readonly(state)
  return [get, set]
}

export const usePersistedState = <K, T>(key: K, defaultValue: T, cachePeriod?: number): any => {
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

  return [readonly(state), set]
}
