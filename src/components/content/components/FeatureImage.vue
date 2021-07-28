<template>
  <div class="feature-image__container">
    <div v-if="showFeatureImage" class="background__wrapper background__wrapper--image">
      <Image
        :src="$props.data.featureImage.original"
        :alt="$props.data.title"
        @error="handleImageError"
      />
    </div>
    <div v-else class="background__wrapper background__wrapper--pattern"></div>
    <div class="post-info__wrapper">
      <div class="row__wrapper--title">
        <span>{{ $props.data.title }}</span>
      </div>
      <div class="row__wrapper--info">
        <div class="flex-box">
          <div class="column__wrapper--avatar">
            <div class="image__wrapper">
              <Image
                :src="$props.data.author.avatar['96']"
                :avatar="true"
                :alt="$props.data.author.nickname"
              ></Image>
            </div>
          </div>
        </div>
        <div class="flex-box">
          <div class="column__wrapper--author">
            <span>{{ $props.data.author.nickname }}</span>
          </div>
        </div>
        <div class="flex-box">
          <div class="column__wrapper--publish">
            <span>{{ $props.data.publistTime }}</span>
          </div>
        </div>
        <div class="flex-box">
          <div class="column__wrapper--words">
            <span>{{ $props.data.wordCount }}</span>
          </div>
        </div>
        <div class="flex-box">
          <div class="column__wrapper--reads">
            <span>{{ $props.data.readCount }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useState } from '@/hooks'

export default defineComponent({
  props: {
    data: Object,
  },
  setup(props) {
    const [imageError, setImageError] = useState(false)
    const showFeatureImage = computed(() => {
      return props.data?.featureImage.original && !imageError.value
    })

    const handleImageError = () => {
      setImageError(true)
    }

    return { showFeatureImage, handleImageError }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/text';
@use '@/styles/mixins/polyfills';
.feature-image__container {
  width: 100%;
  height: 400px;
  display: flex;
  justify-content: center;
  align-items: flex-end;
  position: relative;
  .background__wrapper {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    &--image {
    }
    &--pattern {
      background: yellowgreen;
    }
  }
  .post-info__wrapper {
    width: 100%;
    max-width: 800px;
    padding-bottom: 24px;
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-end;
    align-items: flex-start;
    > * {
      display: flex;
      flex-flow: row nowrap;
      justify-content: flex-start;
      align-items: center;
      text-shadow: 2px 2px 10px #000; // TODO: mixin
    }
    .row__wrapper {
      &--title {
        span {
          line-height: 48px;
          font-size: xx-large;
          color: #ffffff;
          // @include text.line-number-limit(1);
          // @include text.text-shadow-offset;
        }
      }
      &--info {
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-start;
        align-items: center;
        padding-top: 12px;
        @include polyfills.flex-gap(6px, 'row nowrap');
        > .flex-box {
          > * {
            flex: 1 1 auto;
            display: flex;
            flex-flow: row nowrap;
            justify-content: center;
            align-items: center;

            @include polyfills.flex-gap(6px, 'row nowrap');
            span {
              line-height: 20px;
              font-size: medium;
              color: #ffffff;
              text-transform: lowercase;
            }
          }
          &:not(:first-child):not(:last-child) {
            > * {
              &::after {
                content: '·';
                line-height: 20px;
                font-size: medium;
                color: #ffffff;
              }
            }
          }
          .column__wrapper {
            &--avatar {
              flex: 0 0 auto;
              > .image__wrapper {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                overflow: hidden;
                margin-right: 6px;
              }
            }
            &--author {
            }
            &--publish {
            }
            &--words {
            }
            &--reads {
            }
          }
        }
      }
    }
  }
}
</style>
