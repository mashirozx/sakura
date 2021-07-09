<template>
  <div class="composer__container">
    <div class="row__wrapper--tips">
      <span><i class="fab fa-markdown"></i> {{ messages.markdownTips }}</span>
    </div>
    <div class="row__wrapper--textarea">
      <OutlinedTextarea
        v-model:content="inputContent"
        :label="messages.textareaLabel"
        :enableResizer="true"
      ></OutlinedTextarea>
    </div>
    <div class="row__wrapper--profile">
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
      <div class="column__wrapper--input">
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
    <!-- <div class="row__wrapper--options"></div> -->
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
import { useIntl, useState } from '@/hooks'
import gravatar, { WP_DEFAULT_USER_EMAIL } from '@/utils/gravatar'
import Captcha from './Captcha.vue'
import OutlinedInput from '@/components/inputs/OutlinedInput.vue'
import OutlinedTextarea from '@/components/inputs/OutlinedTextarea.vue'
import NormalButton from '@/components/buttons/NormalButton.vue'

export default defineComponent({
  components: { Captcha, OutlinedInput, OutlinedTextarea, NormalButton },
  emits: ['submit'],
  setup(props, { emit }) {
    const Intl = useIntl()
    const messages = {
      markdownTips: Intl.formatMessage({
        id: 'posts.comment.composer.tips.markdownSupported',
        defaultMessage: 'Markdown Supported',
      }),
      textareaLabel: Intl.formatMessage({
        id: 'posts.comment.composer.content.label',
        defaultMessage: 'You are a surprise that I will only meet once in my life',
      }),
      nickname: Intl.formatMessage({
        id: 'posts.comment.composer.authorName.label',
        defaultMessage: 'Nickname *',
      }),
      email: Intl.formatMessage({
        id: 'posts.comment.composer.authorEmail.label',
        defaultMessage: 'Email *',
      }),
      link: Intl.formatMessage({
        id: 'posts.comment.composer.authorUrl.label',
        defaultMessage: 'Link',
      }),
      submit: Intl.formatMessage({
        id: 'posts.comment.composer.submit.button',
        defaultMessage: 'Submit',
      }),
    }

    const inputContent = ref('')
    const inputAuthorName = ref('')
    const inputAuthorEmail = ref('')
    const inputAuthorUrl = ref('')

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
    }
  },
})
</script>

<style lang="scss" scoped>
.composer__container {
  --mdc-theme-primary: orange;
  width: 100%;
  > * {
    width: 100%;
    padding-top: 12px;
  }
  > .row__wrapper {
    &--tips {
      span {
        line-height: 24px;
        font-size: medium;
        color: #404040;
      }
    }
    &--textarea {
      width: 100%;
      ::v-deep(.mdc-text-field__resizer) {
        background-image: url(https://view.moezx.cc/images/2018/03/24/comment-bg.png);
        background-size: contain;
        background-repeat: no-repeat;
        background-position: right;
      }
    }
    &--profile {
      display: flex;
      flex-flow: row nowrap;
      justify-content: space-between;
      align-items: center;
      gap: 12px;
      .column__wrapper {
        &--avatar {
          flex: 0 0 auto;
          position: relative;
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
