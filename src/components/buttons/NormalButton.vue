<template>
  <div class="button__container">
    <div
      :class="[
        'mdc-button',
        { 'mdc-button--outlined': $props.outlined },
        { 'mdc-button--raised': $props.contained },
      ]"
      :ref="setButtonRef"
    >
      <span class="mdc-button__ripple"></span>
      <slot name="icon" v-if="haveIcon">
        <i :class="['mdc-button__icon', $props.icon]" aria-hidden="true"></i>
      </slot>
      <span class="mdc-button__label">{{ $props.context }}</span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useMDCRipple, useElementRef } from '@/hooks'

export default defineComponent({
  name: 'NormalIcon',
  props: {
    icon: String,
    context: String,
    outlined: Boolean,
    contained: Boolean,
  },
  setup(props, { slots }) {
    const [buttonRef, setButtonRef] = useElementRef()

    useMDCRipple(buttonRef)

    const haveIcon = computed(() => {
      return slots.icon ?? props.icon
    })

    return { setButtonRef, haveIcon }
  },
})
</script>

<style lang="scss" scoped>
.mdc-button--outlined:not(:disabled) {
  border-color: rgba(0, 0, 0, 0.38);
  color: rgba(0, 0, 0, 0.6);
}
.mdc-button--outlined:not(:disabled):hover {
  border-color: var(--mdc-theme-primary, rgba(0, 0, 0, 0.12));
  color: var(--mdc-theme-primary, #6200ee);
}
</style>
