<template>
  <div class="card__container">
    <div class="row__wrapper--thumbnail">
      <Image
        class="image"
        :src="data.featureImage.thumbnail"
        :alt="data.title"
        placeholder="https://via.placeholder.com/1024x768"
        :draggable="false"
      />
    </div>
    <div class="row__wrapper--title">
      <span>{{ data.title }}</span>
    </div>
    <div class="row__wrapper--statistics">
      <div class="column__wrapper--read_count">
        <span><i class="fab fa-hotjar"></i> {{ data.readCount }}</span>
      </div>
      <div class="column__wrapper--comment_count">
        <span> <i class="far fa-comment-dots"></i> {{ data.commentCount }}</span>
      </div>
      <div class="column__wrapper--word_count">
        <span><i class="fas fa-pen-nib"></i> {{ data.wordCount }}</span>
      </div>
    </div>
    <div class="row__wrapper--abstract">
      <span>{{ data.excerpt }} </span>
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

    const data = props.data

    const handleViewPostDetailEvent = () => {
      if (data) {
        linkHandler.handleClickLink({ url: data.value?.link ?? '', router, target: '_blank' })
      }
    }

    return {
      data,
      buttonContext,
      handleViewPostDetailEvent,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/text';
@use '@/styles/mixins/tags';

.card__container {
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
  justify-content: flex-start;
  align-items: center;
  gap: 12px;
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
        gap: 12px;
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
