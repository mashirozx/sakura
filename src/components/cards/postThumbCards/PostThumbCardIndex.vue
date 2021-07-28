<template>
  <PostThumbCardMobile v-if="isMobile" :data="data" :type="$props.type"></PostThumbCardMobile>
  <PostThumbCardClassic v-else :data="data" :type="$props.type"></PostThumbCardClassic>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useWindowResize } from '@/hooks'
import postFilter from '@/utils/filters/postFilter'
import PostThumbCardClassic from './PostThumbCardClassic.vue'
import PostThumbCardMobile from './PostThumbCardMobile.vue'
// import { post as postMock } from '@/mocks/postContentMock' // mock

export default defineComponent({
  components: { PostThumbCardClassic, PostThumbCardMobile },
  props: {
    post: { type: Object /*, default: () => postMock*/ },
    type: { type: String, default: 'normal' }, // normal | reverse | mobile
  },
  setup(props) {
    const windowSize = useWindowResize()
    const isMobile = computed(() => windowSize.value.innerWidth <= 840)

    const data = computed(() => (props.post ? postFilter(props.post as Post, 'thumbList') : null))

    return {
      data,
      isMobile,
    }
  },
})
</script>
