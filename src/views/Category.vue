<template>
  <Base class="base">
    <div class="main__content">
      <div class="content__wrapper">
        <PostThumbList :namespace="namespace" :fetchParameters="fetchParameters"></PostThumbList>
      </div>
    </div>
  </Base>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useRoute } from '@/hooks'
import Base from '@/layouts/Base.vue'
import PostThumbList from '@/components/lists/postThumbList/PostThumbList.vue'

export default defineComponent({
  components: { Base, PostThumbList },
  setup() {
    const route = useRoute()
    const { cat1, cat2, cat3 } = route.params
    const categories = [cat1, cat2, cat3].filter((cat) => cat)
    const namespace = ['categories', ...categories].join('-')
    const fetchParameters = { categoriesSlug: categories.join(',') }
    return {
      namespace,
      fetchParameters,
    }
  },
})
</script>
