<template>
  <div class="layout mdc-card mdc-card--outlined">
    <div class="tab-bar__wrapper">
      <TabBar></TabBar>
    </div>
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
    <br /><br /><br /><br /><br /><br /><br />
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import TabBar from '@/components/tabBar/TabBar.vue'

export default defineComponent({
  components: { TabBar },
  setup() {},
})
</script>

<style lang="scss" scoped>
.layout {
  width: 100%;
  overflow: hidden;
  > .tab-bar__wrapper {
    width: 100%;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }
}
</style>
