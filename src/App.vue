<template>
  <router-view v-slot="{ Component }">
    <keep-alive>
      <component :is="Component" :key="$route.fullPath"></component>
    </keep-alive>
  </router-view>
  <div class="messages__wrapper">
    <Messages position-y="bottom" position-x="left"></Messages>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { init } from '@/store'
import { useInjector } from '@/hooks'
import Messages from '@/components/messages/Messages.vue'

export default defineComponent({
  components: { Messages },
  setup() {
    const { fetchWpJson } = useInjector(init)
    fetchWpJson()
  },
})
</script>

<style lang="scss">
@use '@/styles/global';

@include global.normalize;

.messages__wrapper {
  position: fixed;
  bottom: 0;
  left: 0;
  z-index: 999999;
}
</style>
