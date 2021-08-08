<template>
  <div class="composer__container">
    <div class="row__wrapper--textarea">
      <img
        class="background"
        src="https://view.moezx.cc/images/2018/03/24/comment-bg.png"
        :data-show="inputContent.trim().length === 0"
      />
      <OutlinedTextarea
        v-model:content="inputContent"
        :label="messages.textareaLabel"
        :enableResizer="true"
        :enableCounter="true"
        :maxlength="99999"
      ></OutlinedTextarea>
      <div class="toolkits__wrapper">
        <div class="toolkits__container">
          <span class="markdown__tip" ref="toolkitMarkdownRef">
            <i class="fab fa-markdown"></i>
          </span>
          <span class="emoji__tool" ref="toolkitEmojiRef">
            <i class="far fa-laugh-squint"></i>
          </span>
          <span class="image__tool" ref="toolkitImageRef">
            <i class="far fa-image"></i>
          </span>
          <span class="preview__tool" ref="toolkitPreviewRef">
            <i class="fas fa-glasses"></i>
          </span>
          <span
            class="privacy__tool"
            ref="toolkitPrivacyRef"
            @click="handleTogglePrivacyOptionsEvent"
          >
            <i class="fas fa-user-shield"></i>
          </span>
        </div>
      </div>
    </div>
    <div class="row__wrapper--privacy">
      <Toggler :show="shouldShowPrivacyOptions">
        <div class="options__wrapper">
          <div class="option visibility" v-tippy="{ content: messages.privacy.visibility.title }">
            <Switcher
              v-model:checked="privacyIsPrivate"
              positiveLabel=""
              negativeLabel=""
              :disableRipple="true"
            >
              <template #label-positive>
                <span>
                  <i class="fas fa-lock"></i>
                  {{ messages.privacy.visibility.positive }}
                </span>
              </template>
              <template #label-negative>
                <span>
                  <i class="fas fa-unlock"></i>
                  {{ messages.privacy.visibility.negative }}
                </span>
              </template>
            </Switcher>
          </div>
          <div class="option anynomous" v-tippy="{ content: messages.privacy.anynomous.title }">
            <Switcher
              v-model:checked="privacyIsAnynomous"
              positiveLabel=""
              negativeLabel=""
              :disableRipple="true"
            >
              <template #label-positive>
                <span>
                  <i class="fas fa-user-secret"></i>
                  {{ messages.privacy.anynomous.positive }}
                </span>
              </template>
              <template #label-negative>
                <span>
                  <i class="fas fa-user-tie"></i>
                  {{ messages.privacy.anynomous.negative }}
                </span>
              </template>
            </Switcher>
          </div>
          <div class="option subscribe" v-tippy="{ content: messages.privacy.subscribe.title }">
            <Switcher
              v-model:checked="privacyIsSubscribe"
              positiveLabel=""
              negativeLabel=""
              :disableRipple="true"
            >
              <template #label-positive>
                <span>
                  <i class="fas fa-bell"></i>
                  {{ messages.privacy.subscribe.positive }}
                </span>
              </template>
              <template #label-negative>
                <span>
                  <i class="fas fa-bell-slash"></i>
                  {{ messages.privacy.subscribe.negative }}
                </span>
              </template>
            </Switcher>
          </div>
        </div>
      </Toggler>
    </div>
    <div class="row__wrapper--profile">
      <div class="flex-box">
        <div class="column__wrapper--avatar">
          <div class="avatar__wrapper mdc-elevation--z1">
            <Image :src="avatar" placeholder="" :avatar="false" alt="" :draggable="false"></Image>
          </div>
          <div class="icon__wrapper avatar__wrapper mdc-elevation--z2">
            <span class="gravatar">
              <!-- <i class="fab fa-qq"></i> -->
              <i class="fab fa-google"></i>
            </span>
          </div>
        </div>
        <div class="column__wrapper--input username">
          <OutlinedInput
            v-model:content="inputAuthorName"
            leadingIcon="fas fa-user"
            :label="messages.nickname"
          ></OutlinedInput>
        </div>
        <div class="column__wrapper--input">
          <OutlinedInput
            v-model:content="inputAuthorEmail"
            leadingIcon="fas fa-envelope"
            :label="messages.email"
            @blur="handleEmailInputBlurEvent"
          ></OutlinedInput>
        </div>
        <div class="column__wrapper--input">
          <OutlinedInput
            v-model:content="inputAuthorUrl"
            leadingIcon="fas fa-home"
            :label="messages.link"
          ></OutlinedInput>
        </div>
      </div>
    </div>
    <!-- <div class="captcha-button">
      <Captcha></Captcha>
    </div> -->
    <div class="row__wrapper--buttons">
      <NormalButton
        :context="messages.submit"
        :outlined="true"
        @click="handleSubmitEvent"
      ></NormalButton>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue'
import { useTippy, useSingleton } from 'vue-tippy'
import { useIntl, useState } from '@/hooks'
import gravatar, { WP_DEFAULT_USER_EMAIL } from '@/utils/gravatar'
import Captcha from './Captcha.vue'
import OutlinedInput from '@/components/inputs/OutlinedInput.vue'
import OutlinedTextarea from '@/components/inputs/OutlinedTextarea.vue'
import NormalButton from '@/components/buttons/NormalButton.vue'
import Toggler from '@/components/toggler/Toggler.vue'
import Switcher from '@/components/switcher/Switcher.vue'

export default defineComponent({
  components: { Captcha, OutlinedInput, OutlinedTextarea, NormalButton, Toggler, Switcher },
  emits: ['submit'],
  setup(props, { emit }) {
    const intl = useIntl()
    const messages = {
      textareaLabel: intl.formatMessage({
        id: 'posts.comment.composer.content.label',
        defaultMessage: 'You are a surprise that I will only meet once in my life',
      }),
      nickname: intl.formatMessage({
        id: 'posts.comment.composer.authorName.label',
        defaultMessage: 'Nickname *',
      }),
      email: intl.formatMessage({
        id: 'posts.comment.composer.authorEmail.label',
        defaultMessage: 'Email *',
      }),
      link: intl.formatMessage({
        id: 'posts.comment.composer.authorUrl.label',
        defaultMessage: 'Link',
      }),
      submit: intl.formatMessage({
        id: 'posts.comment.composer.submit.button',
        defaultMessage: 'Submit',
      }),
      toolkits: {
        previewTooltip: intl.formatMessage({
          id: 'posts.comment.composer.toolkits.preview.tooltip',
          defaultMessage: '\'<i class="fab fa-markdown"></i>\' Markdown preview',
        }),
        emojiTooltip: intl.formatMessage({
          id: 'posts.comment.composer.toolkits.emoji.tooltip',
          defaultMessage: 'Insert emoji',
        }),
        imageTooltip: intl.formatMessage({
          id: 'posts.comment.composer.toolkits.image.tooltip',
          defaultMessage: 'Attach image',
        }),
        privacyTooltip: intl.formatMessage({
          id: 'posts.comment.composer.toolkits.privacy.tooltip',
          defaultMessage: 'Privacy settings',
        }),
        markdownTooltip: intl.formatMessage({
          id: 'posts.comment.composer.toolkits.markdown.tooltip',
          defaultMessage:
            '\'<a href="https://guides.github.com/features/mastering-markdown/" target="_blank">Markdown</a>\' supported',
        }),
      },
      privacy: {
        anynomous: {
          title: intl.formatMessage({
            id: 'posts.comment.composer.privacy.anynomous.title',
            defaultMessage: 'Whether to comment as an anynomous user?',
          }),
          positive: intl.formatMessage({
            id: 'posts.comment.composer.privacy.anynomous.positive',
            defaultMessage: 'Anynomous',
          }),
          negative: intl.formatMessage({
            id: 'posts.comment.composer.privacy.anynomous.negative',
            defaultMessage: 'Autonym',
          }),
        },
        visibility: {
          title: intl.formatMessage({
            id: 'posts.comment.composer.privacy.visibility.title',
            defaultMessage:
              'Whether to create secret comment that only admins and peoples mentioned can see?',
          }),
          positive: intl.formatMessage({
            id: 'posts.comment.composer.privacy.visibility.positive',
            defaultMessage: 'Secret',
          }),
          negative: intl.formatMessage({
            id: 'posts.comment.composer.privacy.visibility.negative',
            defaultMessage: 'Public',
          }),
        },
        subscribe: {
          title: intl.formatMessage({
            id: 'posts.comment.composer.privacy.subscribe.title',
            defaultMessage: 'Whether to inform you with email when receiving reply?',
          }),
          positive: intl.formatMessage({
            id: 'posts.comment.composer.privacy.subscribe.positive',
            defaultMessage: 'Subscribe',
          }),
          negative: intl.formatMessage({
            id: 'posts.comment.composer.privacy.subscribe.negative',
            defaultMessage: 'Unsubscribe',
          }),
        },
      },
    }

    const inputContent = ref('')
    const inputAuthorName = ref('')
    const inputAuthorEmail = ref('')
    const inputAuthorUrl = ref('')

    const privacyIsPrivate = ref(false)
    const privacyIsAnynomous = ref(false)
    const privacyIsSubscribe = ref(true)

    // TODO: debounce
    const handleSubmitEvent = () => {
      emit('submit', {
        content: inputContent.value,
        authorName: inputAuthorName.value,
        authorEmail: inputAuthorEmail.value,
        authorUrl: inputAuthorUrl.value,
      })
    }

    const [avatar, setAvatar] = useState(gravatar(WP_DEFAULT_USER_EMAIL))

    const handleEmailInputBlurEvent = () =>
      setAvatar(gravatar(inputAuthorEmail.value || WP_DEFAULT_USER_EMAIL))

    const clearInputContent = () => (inputContent.value = '')

    const toolkitEmojiRef = ref()
    const toolkitPreviewRef = ref()
    const toolkitImageRef = ref()
    const toolkitPrivacyRef = ref()
    const toolkitMarkdownRef = ref()
    const commonTippyOpts = {
      allowHTML: true,
      interactive: true,
      animation: 'scale',
      theme: 'material',
    }
    const { tippy: tippyToolkitEmoji } = useTippy(toolkitEmojiRef, {
      content: messages.toolkits.emojiTooltip,
      // ...commonTippyOpts,
    })
    const { tippy: tippyToolkitPreview } = useTippy(toolkitPreviewRef, {
      content: messages.toolkits.previewTooltip,
      // ...commonTippyOpts,
    })
    const { tippy: tippyToolkitImage } = useTippy(toolkitImageRef, {
      content: messages.toolkits.imageTooltip,
      // ...commonTippyOpts,
    })
    const { tippy: tippyToolkitPrivacy } = useTippy(toolkitPrivacyRef, {
      content: messages.toolkits.privacyTooltip,
      // ...commonTippyOpts,
    })
    const { tippy: tippyToolkitMarkdown } = useTippy(toolkitMarkdownRef, {
      content: messages.toolkits.markdownTooltip,
      // ...commonTippyOpts,
    })

    useSingleton(
      [
        tippyToolkitEmoji,
        tippyToolkitPreview,
        tippyToolkitImage,
        tippyToolkitPrivacy,
        tippyToolkitMarkdown,
      ],
      {
        placement: 'top',
        moveTransition: 'transform 0.2s ease-out',
        ...commonTippyOpts,
      }
    )

    const [shouldShowPrivacyOptions, setShouldShowPrivacyOptions] = useState(false)

    const handleTogglePrivacyOptionsEvent = () =>
      setShouldShowPrivacyOptions(!shouldShowPrivacyOptions.value)

    return {
      messages,
      inputContent,
      inputAuthorName,
      inputAuthorEmail,
      inputAuthorUrl,
      avatar,
      handleSubmitEvent,
      handleEmailInputBlurEvent,
      clearInputContent,
      toolkitEmojiRef,
      toolkitPreviewRef,
      toolkitImageRef,
      toolkitPrivacyRef,
      toolkitMarkdownRef,
      shouldShowPrivacyOptions,
      handleTogglePrivacyOptionsEvent,
      privacyIsPrivate,
      privacyIsAnynomous,
      privacyIsSubscribe,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/polyfills';
.composer__container {
  --mdc-theme-primary: orange;
  width: 100%;
  > * {
    width: 100%;
    padding-top: 12px;
    &:first-child {
      padding-top: 0;
    }
  }
  > .row__wrapper {
    &--textarea {
      position: relative;
      width: 100%;
      .background {
        position: absolute;
        bottom: 32px;
        right: 0;
        z-index: -1;
        max-height: 200px;
        max-width: 100%;
        opacity: 0;
        transition: opacity 0.5s;
        &[data-show='true'] {
          opacity: 1;
        }
      }
      .toolkits__wrapper {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 36px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        .toolkits__container {
          padding-left: 12px;
          display: flex;
          flex-flow: row nowrap;
          justify-content: flex-end;
          align-items: center;
          @include polyfills.flex-gap(12px, 'row nowrap');
          > * {
            width: 20px;
            height: 20px;
          }
          span {
            line-height: 24px;
            font-size: medium;
            color: #404040;
          }
        }
      }
    }
    &--privacy {
      padding-top: 0;
      width: 100%;
      .options__wrapper {
        width: 100%;
        padding-top: 12px;
        display: flex;
        flex-flow: row wrap;
        justify-content: space-between;
        align-items: center;
        gap: 6px;
        .option {
          --flex-flow: row-reverse nowrap;
          display: flex;
          flex-flow: row nowrap;
          justify-content: flex-start;
          align-items: center;
          @include polyfills.flex-gap(12px, 'row nowrap');
        }
      }
    }
    &--profile {
      > .flex-box {
        position: relative;
        display: flex;
        flex-flow: row nowrap;
        justify-content: space-between;
        align-items: center;
        @include polyfills.flex-gap(12px, 'row nowrap');
        @media screen and (max-width: 800px) {
          flex-flow: column nowrap;
          @include polyfills.flex-gap-unset('row nowrap');
          @include polyfills.flex-gap(12px, 'column nowrap');
        }
        .column__wrapper {
          &--avatar {
            flex: 0 0 auto;
            position: relative;
            @media screen and (max-width: 800px) {
              position: absolute;
              top: 0;
              right: 0;
              transform: scale(0.8);
            }
            > .avatar__wrapper {
              width: 56px;
              height: 56px;
              border-radius: 50%;
              overflow: hidden;
            }
            > .icon__wrapper {
              position: absolute;
              right: 0;
              bottom: 0;
              width: 20px;
              height: 20px;
              background: #03a9f4;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              span {
                width: 12px;
                height: 12px;
                color: #fff;
                line-height: 12px;
                font-size: small;
                &.gravatar {
                  transform: rotate(270deg);
                }
              }
            }
          }
          &--input {
            flex: 1 1 auto;
            width: 100%;
            @media screen and (max-width: 800px) {
              &.username {
                ::v-deep() {
                  .mdc-text-field__input {
                    width: calc(100% - 80px);
                  }
                }
              }
            }
          }
        }
      }
    }
    &--buttons {
      ::v-deep(.mdc-button) {
        width: 100%;
        height: 50px;
      }
      ::v-deep(.mdc-button--outlined:not(:disabled):hover) {
        border-color: var(--mdc-theme-primary, rgba(0, 0, 0, 0.12));
      }
    }
  }
}
</style>
