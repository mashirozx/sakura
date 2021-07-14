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
@use './styles/index';
.sakura-options-page__app {
  width: calc(100% - 20px);
  padding: 20px 20px 20px 0;
  > .app__wrapper {
    width: 100%;
  }
}
</style>
