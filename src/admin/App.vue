<template>
  <div class="app__wrapper">
    <Core></Core>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Core from './Core.vue'

export default defineComponent({
  components: { Core },
})
</script>

<style lang="scss">
@use './index';
.sakura-options-page__app {
  width: calc(100% - 20px);
  padding: 20px 20px 20px 0;
  @media screen and (max-width: 782px) {
    width: calc(100% - 10px);
    padding: 10px 10px 10px 0;
  }
  > .app__wrapper {
    width: 100%;
  }
}
</style>
