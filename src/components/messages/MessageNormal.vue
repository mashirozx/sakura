<template>
  <div class="item__container mdc-card mdc-card--outlined" :type="$props.message.type">
    <div class="item__content">
      <div class="flex-box">
        <div class="column__wrapper--icon">
          <span><i :class="icon"></i></span>
        </div>
        <div class="column__wrapper--content">
          <div class="row__wrapper--title">
            <div class="title__content--message">
              <div class="title">
                <span>{{ $props.message.title }}</span>
              </div>
              <div
                class="detailed"
                :style="{ height: shouldShowDetail ? `${expandContentHeight}px` : '0px' }"
              >
                <div :class="['content', { show: shouldShowDetail }]" :ref="setExpandContentRef">
                  <span>{{ $props.message.detail }}</span>
                </div>
              </div>
            </div>
            <div
              v-if="$props.message.detail"
              :class="['title__content--collapse', { reverse: shouldShowDetail }]"
              :title="msg.showDetails"
              @click="handleShowDetailClick"
            >
              <i class="fas fa-angle-double-down"></i>
            </div>
            <div class="title__content--close" :title="msg.close" @click="handleCloseMessageEvent">
              <i class="fas fa-times-circle"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent } from 'vue'
import { useIntl, useInjector, useState, useElementRef, useResizeObserver } from '@/hooks'
import { messages } from '@/store'
import NormalButton from '@/components/buttons/NormalButton.vue'

export default defineComponent({
  components: { NormalButton },
  props: { message: Object },
  setup(props) {
    const intl = useIntl()
    const msg = {
      dismiss: intl.formatMessage({
        id: 'messages.popup.dismiss',
        defaultMessage: 'Dismiss',
      }),
      close: intl.formatMessage({
        id: 'messages.popup.close',
        defaultMessage: 'Close',
      }),
      showDetails: intl.formatMessage({
        id: 'messages.popup.showDetails',
        defaultMessage: 'Show details',
      }),
    }

    const icon = computed(() => {
      switch (props.message?.type) {
        case 'success':
          return 'fas fa-check-circle'
        case 'warning':
          return 'fas fa-exclamation-circle'
        case 'info':
          return 'fas fa-info-circle'
        case 'error':
          return 'fas fa-exclamation-triangle'
        default:
          return 'fas fa-info-circle'
      }
    })

    const { messageList, removeMessage } = useInjector(messages)

    const handleCloseMessageEvent = () => {
      if (props.message) removeMessage(messageList, props.message.id)
    }

    const [shouldShowDetail, setShouldShowDetail] = useState(false)
    const handleShowDetailClick = () => {
      setShouldShowDetail(!shouldShowDetail.value)
    }

    const [expandContentRef, setExpandContentRef] = useElementRef()
    const expandContentSize = useResizeObserver(expandContentRef)
    const expandContentHeight = computed(() =>
      isNaN(expandContentSize.value.height)
        ? 0
        : expandContentSize.value.height + expandContentSize.value.paddingTop
    )

    return {
      msg,
      icon,
      handleCloseMessageEvent,
      shouldShowDetail,
      handleShowDetailClick,
      setExpandContentRef,
      expandContentHeight,
    }
  },
})
</script>

<style lang="scss" scoped>
@use "sass:color";
@use '@/styles/mixins/polyfills';

.item__container {
  --text-color: #3c434a;
  --text-color-lighter-30: color.adjust(#3c434a, $lightness: 30%);
  --background-color: #ffffff;
  &[type='success'] {
    --highlight-color: #00b74a;
  }
  &[type='warning'] {
    --highlight-color: #ffa900;
  }
  &[type='info'] {
    --highlight-color: #39c0ed;
  }
  &[type='error'] {
    --highlight-color: #f93154; // danger
  }
  width: var(--width);
  background: var(--background-color);
  border-left: 3px solid var(--highlight-color, #757575);
  > .item__content {
    width: calc(100% - 24px);
    padding: 12px;
    > .flex-box {
      display: flex;
      flex-flow: row nowrap;
      align-items: space-between;
      align-items: flex-start;
      @include polyfills.flex-gap(12px, 'row nowrap');
      > .column__wrapper {
        &--icon {
          flex: 0 0 auto;
          span {
            color: var(--highlight-color, #757575);
            font-size: medium;
          }
        }
        &--content {
          flex: 1 1 auto;
          width: 100%;
          display: flex;
          flex-flow: column nowrap;
          align-items: flex-start;
          > .row__wrapper {
            &--title {
              width: 100%;
              display: flex;
              flex-flow: row nowrap;
              justify-content: space-between;
              align-items: flex-start;
              @include polyfills.flex-gap(12px, 'row nowrap');
              > * span {
                line-height: 16px;
              }
              > .title__content {
                &--message {
                  flex: 1 1 auto;
                  width: 100%;
                  > .title {
                    span {
                      color: var(--text-color);
                    }
                  }
                  > .detailed {
                    transition: height 0.5s cubic-bezier(0, 0, 0.3, 1);
                    overflow: hidden;
                    > .content {
                      padding-top: 6px;
                      width: 100%;
                      height: auto;
                      transform: scaleY(0);
                      transform-origin: top;
                      transition: transform 0.5s cubic-bezier(0, 0, 0.3, 1);
                      &.show {
                        transform: scaleY(1);
                      }
                      span {
                        color: var(--text-color-lighter-30);
                      }
                    }
                  }
                }
                &--collapse {
                  flex: 0 0 auto;
                  transform: scaleY(1);
                  transition: transform 0.5s cubic-bezier(0, 0, 0.3, 1);
                  &.reverse {
                    transform: scaleY(-1);
                  }
                }
                &--close {
                  flex: 0 0 auto;
                }
              }
            }
            &--buttons {
              align-self: flex-end;
            }
          }
        }
      }
    }
  }
}
</style>
