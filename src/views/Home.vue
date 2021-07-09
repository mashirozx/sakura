<template>
  <Base class="base">
    <div class="main__content">
      <div class="cover__wrapper"> </div>
      <div class="content__wrapper">
        <PostThumbList></PostThumbList>
      </div>
    </div>
  </Base>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useInjector, useRoute } from '@/hooks'
import { posts } from '@/store'
import Base from '@/layouts/Base.vue'
import PostThumbList from '@/components/lists/postThembList/PostThumbList.vue'

export default defineComponent({
  components: { Base, PostThumbList },
  setup() {
    // const route = useRoute()
    // console.log(route.params)
    // const { postsStore, fetchPost } = useInjector(posts)
    // fetchPost(postsStore, { perPage: 1, page: 10 }).then(async () => {
    //   console.log(postsStore.value)
    // })
  },
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
    background: url('https://via.placeholder.com/233');
  }
  .content__wrapper {
    width: 100%;
  }
}
</style>
