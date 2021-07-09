# Composition API version state store

Create store without Vuex

Usage:

```ts
// init in main.ts
import { applyProviders } from '@/hooks/store'
import { auth, init } from '@/store'
const app = createApp(App)
applyProviders(app, auth, init)

// or init in ancestor component
import { useProviders } from '@/hooks'
import { auth, init } from '@/store'
setup() {
  useProviders(auth, init)
}

// use in child component
import { auth } from '@/store'
import { useInjector } from '@/hooks'
setup() {
  const { setApplicationPassword } = useInjector(auth)

  setApplicationPassword(data)
}
```
