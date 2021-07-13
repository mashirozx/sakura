<template>
  <div class="app__wrapper">
    <Layout></Layout>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Layout from './Layout.vue'

export default defineComponent({
  components: { Layout },
})
</script>

<style lang="scss">
.sakura-options-page__app {
  width: calc(100% - 20px);
  padding: 20px 20px 20px 0;
  > .app__wrapper {
    background: yellowgreen;
  }
}
</style>
