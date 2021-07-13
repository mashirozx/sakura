import { createApp } from 'vue'
import { VueSvgIconPlugin } from '@yzfe/vue3-svgicon'
import '@yzfe/svgicon/lib/svgicon.css'
import App from './App.vue'
import { storeProviderPlugin } from '@/hooks/store'
// import { auth, init, posts, comments } from './store'
import { intlPlugin } from '../locales'

const app = createApp(App)
// app.use(storeProviderPlugin, [auth, init, posts, comments])
app.use(intlPlugin)
app.use(VueSvgIconPlugin, { tagName: 'svg-icon' })
app.mount('#app')
