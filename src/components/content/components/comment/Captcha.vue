<template>
  <div class="captcha__container">
    <div class="button__wrapper" :ref="setPopupRef" @click="handleToggleCaptcha">
      <NormalButton
        :context="buttonMsg"
        :icon="showDialog ? 'fas fa-times' : 'fas fa-shield-alt'"
      ></NormalButton>
      <div :class="['popup', 'mdc-elevation--z8', { show: showDialog }]">
        <div class="captcha__wrapper">
          <ReCaptcha2></ReCaptcha2>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { useState, useElementRef, useIntl } from '@/hooks'
import NormalButton from '@/components/buttons/NormalButton.vue'
import ReCaptcha2 from '@/components/captcha/ReCaptcha2.vue'

export default defineComponent({
  components: { NormalButton, ReCaptcha2 },
  setup() {
    const Intl = useIntl()

    const buttonMsg = Intl.formatMessage({
      id: 'posts.comment.composer.captcha.toggleButton',
      defaultMessage: 'Captcha',
    })

    const [showDialog, setShowDialog] = useState(false)
    const [popupRef, setPopupRef] = useElementRef()
    const handleToggleCaptcha = () => setShowDialog(!showDialog.value)
    onClickOutside(popupRef, () => setShowDialog(false))

    return { showDialog, handleToggleCaptcha, setPopupRef, buttonMsg }
  },
})
</script>

<style lang="scss" scoped>
.captcha__container {
  .button__wrapper {
    --mdc-theme-primary: #5f6368;
    position: relative;
    .popup {
      position: absolute;
      bottom: calc(100% + 16px);
      left: 50%;
      background: #fff;
      border-radius: 4px;
      transform: translateX(-50%) scale(0, 0);
      transform-origin: center bottom;
      transition: all 0.2s ease-in-out;
      .captcha__wrapper {
        padding: 12px;
      }
      &.show {
        transform: translateX(-50%) scale(1, 1);
      }
      &:after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 50%;
        margin-left: -10px;
        border-width: 10px;
        border-style: solid;
        border-color: #fff transparent transparent transparent;
      }
    }
  }
}
</style>
