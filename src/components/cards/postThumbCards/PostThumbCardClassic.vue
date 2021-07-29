<template>
  <div class="card__container mdc-card mdc-elevation--z8" :type="$props.type" v-if="data">
    <div class="card__content mdc-card__primary-action" :ref="setContentRef">
      <div class="ripple__mask mdc-card__ripple"></div>
      <div class="thumbnail__wrapper">
        <Link :url="$props.data.link">
          <Image
            class="image"
            :src="$props.data.featureImage.thumbnail"
            :alt="$props.data.title"
            placeholder="https://via.placeholder.com/1024x768"
            :draggable="false"
          />
        </Link>
      </div>
      <div class="details__wrapper">
        <div class="row__wrapper--date">
          <span><i class="far fa-clock"></i> {{ $props.data.publistTime }}</span>
        </div>
        <div class="row__wrapper--title">
          <Link :url="$props.data.link">
            <span>{{ $props.data.title }}</span>
          </Link>
        </div>
        <div class="row__wrapper--info">
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
        <div class="row__wrapper--abstruct">
          <span>{{ $props.data.excerpt }} </span>
        </div>
        <div class="row__wrapper--tags" v-if="$props.data.tags.length > 0">
          <div class="tags__container">
            <div class="tag__wrapper" v-for="(tag, index) in $props.data.tags" :key="index">
              <Link :to="{ name: 'TagArchive', params: { tag: tag.slug } }">
                <NormalChip :context="tag.name"></NormalChip>
              </Link>
            </div>
          </div>
        </div>
        <div class="row__wrapper--button">
          <div class="button__wrapper">
            <Link :url="$props.data.link">
              <NormalButton icon="fab fa-readme" :context="buttonContext"></NormalButton>
            </Link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useIntl, useRouter, useElementRef, useMDCRipple } from '@/hooks'
import NormalButton from '@/components/buttons/NormalButton.vue'
import NormalChip from '@/components/chips/NormalChip.vue'

export default defineComponent({
  components: { NormalButton, NormalChip },
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

    return {
      buttonContext,
      setContentRef,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/text';
@use '@/styles/mixins/tags';
// @use '@/styles/mixins/skeleton';
@use '@/styles/mixins/polyfills';

.card__container {
  width: 780px;
  height: 300px;
  background: #ffffff;
  border-radius: 10px;
  user-select: none;
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
          span {
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
        &--tags {
          max-height: 32px;
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
              .router-link {
                text-decoration: none;
              }
            }
          }
        }
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
