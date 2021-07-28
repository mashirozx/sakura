<template>
  <div class="item__container">
    <div class="avatar__wrapper">
      <div class="image__wrapper">
        <Image
          :src="comment.authorAvatarUrls[96]"
          :avatar="true"
          alt="comment.authorName"
          :draggable="false"
        ></Image>
      </div>
    </div>
    <div class="content__wrapper">
      <div class="row__wrapper--profile">
        <span class="name">{{ comment.authorName }}</span>
      </div>
      <div class="row__wrapper--content">
        <div class="content" v-html="contentHtml"> </div>
      </div>
      <div class="row__wrapper--footer">
        <span class="ua">来自iOS客户端</span>
        <span class="time">{{ publistTime }}</span>
        <span class="location">{{ comment.metaFields.userLocation }}</span>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import camelcaseKeys from 'camelcase-keys'
import marked from '@/utils/marked'
import publishTime from '@/utils/filters/publishTime'

export default defineComponent({
  props: { data: Object },
  setup(props) {
    const comment = computed(() => {
      const data = camelcaseKeys(props.data as any)
      data.metaFields = camelcaseKeys(data.metaFields as any)
      return data
    })

    const contentHtml = computed(() => marked(comment.value.content.plain))

    const publistTime = publishTime(comment.value.date)

    return { comment, contentHtml, publistTime }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/polyfills';
@use '@/styles/markdown/github';
.item__container {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  .avatar__wrapper {
    flex: 0 0 auto;
    width: 80px;
    display: flex;
    justify-content: center;
    .image__wrapper {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      overflow: hidden;
    }
  }
  .content__wrapper {
    flex: 1 1 auto;
    width: 100%;
    > * {
      &:not(:first-child) {
        padding-top: 12px;
      }
    }
    .row__wrapper {
      &--profile {
        .name {
          color: #fb7299;
          line-height: 20px;
          font-size: small;
          font-weight: 900;
        }
      }
      &--content {
        width: 100%;
        ::v-deep() {
          @import 'highlight.js/scss/github';
          @include github.markdown-style;
          img {
            max-width: 100%;
          }
        }
        .content {
          width: 100%;
        }
      }
      &--footer {
        color: #99a2aa;
        line-height: 16px;
        font-size: small;
        display: flex;
        flex-flow: row wrap;
        @include polyfills.flex-gap(12px, 'row wrap');
      }
    }
  }
}
</style>
