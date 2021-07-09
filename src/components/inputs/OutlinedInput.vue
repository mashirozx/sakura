<template>
  <label
    :class="[
      'mdc-text-field',
      'mdc-text-field--outlined',
      { 'mdc-text-field--with-leading-icon': $props.leadingIcon },
      { 'mdc-text-field--with-trailing-icon': $props.trailingIcon },
    ]"
    :ref="setTextareaRef"
  >
    <span class="mdc-notched-outline">
      <span class="mdc-notched-outline__leading"></span>
      <span class="mdc-notched-outline__notch">
        <span class="mdc-floating-label" :id="id">{{ $props.label }}</span>
      </span>
      <span class="mdc-notched-outline__trailing"></span>
    </span>
    <i
      v-if="$props.leadingIcon"
      :class="['mdc-text-field__icon', 'mdc-text-field__icon--leading', $props.leadingIcon]"
    ></i>
    <input
      class="mdc-text-field__input"
      :type="$props.text"
      :aria-labelledby="id"
      :tabindex="$props.tabindex"
      v-model="content"
      @blur="handleBlurEvent"
    />
    <i
      v-if="$props.trailingIcon"
      :class="['mdc-text-field__icon', 'mdc-text-field__icon--trailing', $props.trailingIcon]"
      role="button"
    ></i>
  </label>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import { MD5 } from 'crypto-js'
import { useElementRef, useMDCTextField } from '@/hooks'

export default defineComponent({
  props: {
    leadingIcon: String,
    trailingIcon: String,
    label: String,
    tabindex: Number,
    type: { type: String, default: 'text' },
    content: String,
  },
  emits: ['update:content', 'blur'],
  setup(props, { emit }) {
    const id = MD5(Math.random().toString()).toString().slice(0, 8)
    const [textareaRef, setTextareaRef] = useElementRef()
    useMDCTextField(textareaRef)

    const content = ref(props.content)
    watch(content, (value) => emit('update:content', value))
    watch(
      () => props.content,
      (value) => (content.value = value)
    )

    const handleBlurEvent = (event: Event) => {
      emit('blur', event)
    }

    return { id, setTextareaRef, content, handleBlurEvent }
  },
})
</script>

<style lang="scss" scoped>
.mdc-text-field {
  width: 100%;
  // margin-top: var(--margin-top, 4px);
}
.mdc-text-field--focused:not(.mdc-text-field--disabled) .mdc-floating-label {
  color: var(--mdc-theme-primary, rgba(98, 0, 238, 0.87));
}
</style>
