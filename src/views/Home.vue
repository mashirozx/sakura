<template>
  <Base class="base">
    <div class="main__content">
      <div class="cover__wrapper">
        <Cover></Cover>
      </div>
      <div class="content__wrapper">
        <PostThumbList namespace="homepage"></PostThumbList>
      </div>
    </div>
  </Base>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Base from '@/layouts/Base.vue'
import PostThumbList from '@/components/lists/postThumbList/PostThumbList.vue'
import Cover from '@/layouts/components/cover/Cover.vue'

export default defineComponent({
  components: { Base, PostThumbList, Cover },
})
</script>

<style lang="scss" scoped>
.base {
  --header-position: fixed;
}
.main__content {
  .cover__wrapper {
    width: 100%;
    height: 100vh;
    // background: url('https://via.placeholder.com/233');
  }
  .content__wrapper {
    width: 100%;
  }
}
</style>
