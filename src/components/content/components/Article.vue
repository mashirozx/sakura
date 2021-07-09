<template>
  <div class="article__container">
    <div class="article__content">
      <article :class="['article', `${contentType}-body`]" v-html="contentHtml"></article>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useState } from '@/hooks'
import marked from '@/utils/marked'

export default defineComponent({
  props: {
    content: Object,
    theme: { type: String, default: '' },
  },
  setup(props) {
    const [contentType, setContentType] = useState('rendered') // 'markdown
    const contentHtml = computed(() => {
      if (!props.content) {
        return null
      } else if (props.content.markdown) {
        setContentType('markdown')
        return marked(props.content.markdown)
      } else {
        setContentType('rendered')
        return props.content.rendered
      }
    })
    return { contentType, contentHtml }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/markdown/github';
.article__container {
  width: 100%;
  .article__content {
    width: 100%;
    ::v-deep() {
      @import 'highlight.js/scss/github';
      @include github.markdown-style;
    }
    .article {
      width: 100%;
    }
  }
}
</style>
