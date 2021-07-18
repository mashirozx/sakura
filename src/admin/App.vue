<template>
  <div class="app__wrapper" :style="scheme">
    <div class="app__content">
      <Core></Core>
    </div>
  </div>
  <div class="messages__wrapper" :style="scheme">
    <Messages position-y="bottom"></Messages>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import scheme from './scheme'
import Core from './Core.vue'
import Messages from '@/components/messages/Messages.vue'

export default defineComponent({
  components: { Core, Messages },
  setup() {
    return { scheme }
  },
})
</script>

<style lang="scss">
@use './mdc';
@use './variables';
.sakura-options-page__app {
  width: calc(100% - 20px);
  padding: 20px 20px 20px 0;
  @media screen and (max-width: variables.$mobile-max-width) {
    width: calc(100% - 10px);
    padding: 10px 10px 10px 0;
  }
  > .app__wrapper {
    width: 100%;
    .app__content {
      width: 100%;
    }
  }
  > .messages__wrapper {
    position: fixed;
    bottom: 0;
    right: 0;
    z-index: 999999;
  }
}
</style>
