<template>
  <label
    :class="[
      'mdc-text-field',
      'mdc-text-field--outlined',
      'mdc-text-field--textarea',
      { 'mdc-text-field--with-internal-counter': showCounter },
    ]"
    :ref="setTextareaRef"
  >
    <span class="mdc-notched-outline">
      <span class="mdc-notched-outline__leading"></span>
      <span class="mdc-notched-outline__notch">
        <span class="mdc-floating-label" :id="id">{{ label }}</span>
      </span>
      <span class="mdc-notched-outline__trailing"></span>
    </span>
    <span :class="['mdc-text-field__resizer', { 'disable-resize': !$props.enableResizer }]">
      <textarea
        class="mdc-text-field__input"
        :aria-labelledby="id"
        :rows="$props.rows"
        :cols="$props.cols"
        :maxlength="$props.maxlength"
        v-model="content"
      ></textarea>
      <span v-if="showCounter" class="mdc-text-field-character-counter"></span>
    </span>
  </label>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch } from 'vue'
import { MD5 } from 'crypto-js'
import { useElementRef, useMDCTextField } from '@/hooks'

export default defineComponent({
  props: {
    maxlength: { type: Number, default: -1 },
    rows: { type: Number, default: 8 },
    cols: { type: Number, default: 40 },
    enableCounter: { type: Boolean, default: true },
    enableResizer: { type: Boolean, default: true },
    label: String,
    tabindex: Number,
    content: String,
  },
  emits: ['update:content'],
  setup(props, { emit }) {
    const id = MD5(Math.random().toString()).toString().slice(0, 8)

    const [textareaRef, setTextareaRef] = useElementRef()
    useMDCTextField(textareaRef)

    const showCounter = computed(() => {
      return props.maxlength > 0 && props.enableCounter
    })

    const content = ref(props.content)
    watch(content, (value) => emit('update:content', value))
    watch(
      () => props.content,
      (value) => (content.value = value)
    )

    return { id, setTextareaRef, showCounter, content }
  },
})
</script>

<style lang="scss" scoped>
.mdc-text-field {
  width: 100%;
  // margin-top: var(--margin-top, 4px);
}
.disable-resize {
  resize: none;
}
.mdc-text-field--focused:not(.mdc-text-field--disabled) .mdc-floating-label {
  color: var(--mdc-theme-primary, rgba(98, 0, 238, 0.87));
}
</style>
