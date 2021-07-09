<template>
  <div class="single-content__wrapper" v-if="postData">
    <div class="featuer-image__wrapper">
      <FeatureImage :data="postData"></FeatureImage>
    </div>
    <div class="article__wrapper">
      <Article :content="postData.content"></Article>
    </div>
    <div class="content-loader__wrapper" v-show="postFetchStatus === 'fetching'">
      <BookLoader></BookLoader>
    </div>
    <div class="comment__wrapper">
      <Comment :postId="postId"></Comment>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import contentHandler from './utils/contentHandler'
import FeatureImage from './components/FeatureImage.vue'
import Article from './components/Article.vue'
import Comment from './components/comment/Comment.vue'
import BookLoader from '@/components/loader/BookLoader.vue'

export default defineComponent({
  components: { FeatureImage, Article, BookLoader, Comment },
  props: {
    singleType: String, // normal
    pageType: String, // normal
  },
  setup(props) {
    const { postData, postFetchStatus } = contentHandler(props)
    const postId = computed(() => {
      return postData.value?.id
    })
    return { postData, postFetchStatus, postId }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/tags';
@use '@/styles/mixins/skeleton';
.single-content__wrapper {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  .featuer-image__wrapper {
    width: 100%;
  }
  .article__wrapper {
    width: 100%;
    max-width: 800px;
    padding-top: 24px;
  }
  .comment__wrapper {
    width: 100%;
    max-width: 800px;
    padding-top: 24px;
  }
}
</style>
