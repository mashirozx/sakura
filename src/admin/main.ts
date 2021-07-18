import { createApp } from 'vue'
import { VueSvgIconPlugin } from '@yzfe/vue3-svgicon'
import '@yzfe/svgicon/lib/svgicon.css'
import App from './App.vue'
import { storeProviderPlugin } from '@/hooks/store'
import store from './store'
import { messages } from '@/store'
import { intlPlugin } from '../locales'
import UiIcon from '@/components/icon/UiIcon.vue'
import Image from '@/components/image/Image.vue'

const app = createApp(App)
app.use(storeProviderPlugin, [store, messages])
app.use(intlPlugin)
app.use(VueSvgIconPlugin, { tagName: 'svg-icon' })
app.component('UiIcon', UiIcon)
app.component('Image', Image)
app.mount('#app')
