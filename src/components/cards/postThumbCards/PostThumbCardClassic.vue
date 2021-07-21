<template>
  <div class="card__container mdc-card mdc-elevation--z8" :type="$props.type" v-if="data">
    <div class="card__content mdc-card__primary-action" :ref="setContentRef">
      <div class="ripple__mask mdc-card__ripple"></div>
      <div class="thumbnail__wrapper" @click="handleViewPostDetailEvent">
        <Image
          class="image"
          :src="data.featureImage.thumbnail"
          :alt="data.title"
          placeholder="https://via.placeholder.com/1024x768"
          :draggable="false"
        />
      </div>
      <div class="details__wrapper">
        <div class="row__wrapper--date">
          <span><i class="far fa-clock"></i> {{ data.publistTime }}</span>
        </div>
        <div class="row__wrapper--title" @click="handleViewPostDetailEvent">
          <span>{{ data.title }}</span>
        </div>
        <div class="row__wrapper--info">
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
        <div class="row__wrapper--abstruct">
          <span>{{ data.excerpt }} </span>
        </div>
        <!-- <div class="row__wrapper--tags">
          <div class="tags__container">
            <div class="tag__wrapper" v-for="(tag, index) in tags" :key="index">
              <div class="tag yolk">
                <span class="text">{{ tag }}</span>
              </div>
            </div>
          </div>
        </div> -->
        <!-- // TODO: use tags instead of button, button is useless! -->
        <div class="row__wrapper--button" @click="handleViewPostDetailEvent">
          <div class="button__wrapper">
            <NormalButton icon="fab fa-readme" :context="buttonContext"></NormalButton>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useIntl, useRouter, useElementRef, useMDCRipple } from '@/hooks'
import linkHandler from '@/utils/linkHandler'
import NormalButton from '@/components/buttons/NormalButton.vue'

export default defineComponent({
  components: { NormalButton },
  props: {
    data: { type: Object },
    type: { type: String, default: 'normal' }, // normal | reverse | mobile
  },
  setup(props) {
    const intl = useIntl()
    const router = useRouter()

    const [contentRef, setContentRef] = useElementRef()
    useMDCRipple(contentRef)

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
      setContentRef,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/text';
@use '@/styles/mixins/tags';
// @use '@/styles/mixins/skeleton';

.card__container {
  // TODO: sizing in parent
  width: 780px;
  height: 300px;
  background: #ffffff;
  border-radius: 10px;
  .card__content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    .ripple__mask {
      z-index: 9;
    }
    .thumbnail__wrapper {
      flex: 1 1 auto;
      width: 55%;
      height: 100%;
      border-radius: 10px 0 0 10px;
      overflow: hidden;
      cursor: pointer;
      // @include skeleton.skeleton-loading;
      .image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scale(1);
        transition: all 0.3s ease-in-out;
      }
    }
    .details__wrapper {
      flex: 1 1 auto;
      width: 45%;
      height: calc(100% - 24px);
      display: flex;
      flex-flow: column nowrap;
      justify-content: space-between;
      align-items: center;
      > * {
        flex: 1 1 auto;
        width: calc(100% - 24px);
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-start;
        align-items: center;
      }
      > .row__wrapper {
        &--date {
          line-height: 12px;
          font-size: medium;
          color: #999999;
        }
        &--title {
          cursor: pointer;
          > span {
            line-height: 32px;
            font-size: large;
            font-weight: 700;
            color: #333333;
            @include text.line-number-limit(2);
          }
        }
        &--info {
          justify-content: space-between;
          > * {
            cursor: pointer;
            > span {
              line-height: 12px;
              font-size: small;
              color: #999999;
            }
          }
          // .column__wrapper {
          //   &--read_count {
          //   }
          //   &--comment_count {
          //   }
          //   &--word_count {
          //   }
          // }
        }
        &--abstruct {
          > span {
            line-height: 22px;
            font-size: medium;
            @include text.line-number-limit(3);
          }
        }
        // &--tags {
        //   max-height: 16px;
        //   overflow: hidden;
        //   align-items: flex-start;
        //   .tags__container {
        //     display: flex;
        //     flex-flow: row wrap;
        //     justify-content: flex-start;
        //     align-items: center;
        //     gap: 12px;
        //     .tag__wrapper {
        //       display: flex;
        //       flex-flow: row nowrap;
        //       justify-content: flex-start;
        //       align-items: center;
        //       @include tags.tag-style;
        //     }
        //   }
        // }
        &--button {
          justify-content: flex-end;
          .button__wrapper {
            width: auto;
            --mdc-theme-primary: #5f6368;
          }
        }
      }
    }
  }
  &:hover {
    .card__content {
      .thumbnail__wrapper {
        .image {
          transform: scale(1.1);
        }
      }
    }
  }
  &[type='reverse'] {
    .card__content {
      flex-direction: row-reverse;
      .thumbnail__wrapper {
        border-radius: 0 10px 10px 0;
      }
      .details__wrapper {
        > .row__wrapper {
          &--button {
            justify-content: flex-start;
          }
        }
      }
    }
  }
}
</style>
