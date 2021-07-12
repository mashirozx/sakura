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
    console.log(route.params)

    const { tag } = route.params
    const namespace = ['tags', tag].join('-')
    const fetchParameters = { tagsSlug: tag }
    return {
      namespace,
      fetchParameters,
    }
  },
})
</script>
