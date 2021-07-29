<template>
  <div class="single-content__wrapper">
    <div class="featuer-image__wrapper" v-if="postData.publistTimeBrief">
      <FeatureImage :data="postData"></FeatureImage>
    </div>
    <div class="article__wrapper" v-if="postData.content">
      <Article :content="postData.content"></Article>
    </div>
    <div class="content-loader__wrapper" v-show="postFetchStatus === 'pending'">
      <BookLoader></BookLoader>
    </div>
    <div class="comment__wrapper" v-if="postId">
      <Comment :postId="postId"></Comment>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { isEmpty } from 'lodash'
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
      if (isEmpty(postData.value)) return false
      return (postData.value as Post)?.id
    })
    return { postData, postFetchStatus, postId }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/tags';
@use '@/styles/mixins/sizes';
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
  .article__wrapper,
  .comment__wrapper {
    width: calc(100% - 12px * 2);
    max-width: #{sizes.$post-main-content-max-width}; // 800px
    padding: 24px 12px 0 12px;
  }
}
</style>
