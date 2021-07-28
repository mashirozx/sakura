<template>
  <div class="card__container">
    <div class="row__wrapper--thumbnail" @click="handleViewPostDetailEvent">
      <Image
        class="image"
        :src="$props.data.featureImage.thumbnail"
        :alt="$props.data.title"
        placeholder="https://via.placeholder.com/1024x768"
        :draggable="false"
      />
    </div>
    <div class="row__wrapper--title" @click="handleViewPostDetailEvent">
      <span>{{ $props.data.title }}</span>
    </div>
    <div class="row__wrapper--statistics">
      <div class="column__wrapper--read_count">
        <span><i class="fab fa-hotjar"></i> {{ $props.data.readCount }}</span>
      </div>
      <div class="column__wrapper--comment_count">
        <span> <i class="far fa-comment-dots"></i> {{ $props.data.commentCount }}</span>
      </div>
      <div class="column__wrapper--word_count">
        <span><i class="fas fa-pen-nib"></i> {{ $props.data.wordCount }}</span>
      </div>
    </div>
    <div class="row__wrapper--abstract">
      <span>{{ $props.data.excerpt }} </span>
    </div>
    <div class="row__wrapper--tags">
      <div class="tags__container">
        <div
          class="tag__wrapper"
          v-for="(tag, index) in ['vue', 'javascript', 'php', 'wordpress']"
          :key="index"
        >
          <div class="tag yolk">
            <span class="text">{{ tag }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useIntl, useRouter } from '@/hooks'
import linkHandler from '@/utils/linkHandler'
import NormalButton from '@/components/buttons/NormalButton.vue'

export default defineComponent({
  components: { NormalButton },
  props: {
    data: { type: Object /*, default: () => postMock*/ },
    type: { type: String, default: 'normal' }, // normal | reverse | mobile
  },
  setup(props) {
    const intl = useIntl()
    const router = useRouter()

    const buttonContext = intl.formatMessage({
      id: 'posts.readMore',
      defaultMessage: 'Read More',
    })

    const handleViewPostDetailEvent = () => {
      linkHandler.handleClickLink({ url: props.data?.link ?? '', router, target: '_blank' })
    }

    return {
      buttonContext,
      handleViewPostDetailEvent,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/text';
@use '@/styles/mixins/tags';
@use '@/styles/mixins/polyfills';

.card__container {
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
  justify-content: flex-start;
  align-items: center;
  @include polyfills.flex-gap(12px, 'column nowrap');
  > * {
    width: calc(100% - 24px);
  }
  > .row__wrapper {
    &--thumbnail {
      width: 100%;
    }
    &--tags {
      max-height: 16px;
      overflow: hidden;
      align-items: flex-start;
      .tags__container {
        display: flex;
        flex-flow: row wrap;
        justify-content: flex-start;
        align-items: center;
        @include polyfills.flex-gap(12px, 'row wrap');
        .tag__wrapper {
          display: flex;
          flex-flow: row nowrap;
          justify-content: flex-start;
          align-items: center;
          @include tags.tag-style;
        }
      }
    }
    &--title {
      line-height: 30px;
      font-size: x-large; // 24
      font-weight: bold;
    }
    &--abstract {
      line-height: 22px;
      font-size: medium; // 16
      @include text.line-number-limit(4);
    }
    &--statistics {
      display: flex;
      flex-flow: row nowrap;
      justify-content: space-between;
      align-items: center;
      > * {
        cursor: pointer;
        > span {
          line-height: 12px;
          font-size: small;
          color: #999999;
        }
      }
    }
  }
}
</style>
