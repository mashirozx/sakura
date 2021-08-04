import { createApp } from 'vue'
import { VueSvgIconPlugin } from '@yzfe/vue3-svgicon'
import '@yzfe/svgicon/lib/svgicon.css'
import VueTippy from 'vue-tippy'
import 'tippy.js/dist/tippy.css'
import 'tippy.js/animations/scale.css'
import 'tippy.js/themes/material.css'
import 'animate.css/animate.css'
import App from './App.vue'
import router from './router'
import { storeProviderPlugin } from './hooks/store'
import { auth, init, posts, comments, messages } from './store'
import { intlPlugin } from './locales'
import UiIcon from '@/components/icon/UiIcon.vue'
import Image from '@/components/image/Image.vue'
import Link from '@/components/link/Link.vue'

const theWindow = window as any
theWindow.router = router

const app = createApp(App)
app.use(storeProviderPlugin, [auth, init, posts, comments, messages])
app.use(router)
app.use(intlPlugin)
app.use(VueSvgIconPlugin, { tagName: 'svg-icon' })
app.component('UiIcon', UiIcon)
app.component('Image', Image)
app.component('Link', Link)
app.use(VueTippy, { directive: 'tippy', component: 'tippy', componentSingleton: 'tippy-singleton' })
app.mount('#app')
