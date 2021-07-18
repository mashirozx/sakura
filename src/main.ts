import { createApp } from 'vue'
import { VueSvgIconPlugin } from '@yzfe/vue3-svgicon'
import '@yzfe/svgicon/lib/svgicon.css'
import App from './App.vue'
import router from './router'
import { storeProviderPlugin } from './hooks/store'
import { auth, init, posts, comments, messages } from './store'
import { intlPlugin } from './locales'
import UiIcon from '@/components/icon/UiIcon.vue'
import Image from '@/components/image/Image.vue'

const theWindow = window as any
theWindow.router = router

const app = createApp(App)
app.use(storeProviderPlugin, [auth, init, posts, comments, messages])
app.use(router)
app.use(intlPlugin)
app.use(VueSvgIconPlugin, { tagName: 'svg-icon' })
app.component('UiIcon', UiIcon)
app.component('Image', Image)
app.mount('#app')
