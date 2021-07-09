<template>
  <router-view v-slot="{ Component }">
    <keep-alive>
      <component :is="Component" :key="$route.fullPath"></component>
    </keep-alive>
  </router-view>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { init } from '@/store'
import { useInjector } from '@/hooks'

export default defineComponent({
  name: 'App',
  setup() {
    const { fetchWpJson } = useInjector(init)
    fetchWpJson()
  },
})
</script>

<style lang="scss">
@import 'normalize.css/normalize.css';
@import '@/styles/index.scss';
</style>
