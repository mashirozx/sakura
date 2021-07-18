<template>
  <div :class="['checkbox__container', { disabled: $props.disabled }]">
    <div class="mdc-checkbox mdc-checkbox--touch" :ref="setElRef" @change="handleChange">
      <input type="checkbox" class="mdc-checkbox__native-control" :id="`checkbox-${id}`" />
      <div class="mdc-checkbox__background">
        <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
          <path
            class="mdc-checkbox__checkmark-path"
            fill="none"
            d="M1.73,12.91 8.1,19.28 22.79,4.59"
          />
        </svg>
        <div class="mdc-checkbox__mixedmark"></div>
      </div>
      <div class="mdc-checkbox__ripple"></div>
    </div>
    <label class="label" :for="`checkbox-${id}`" :id="`checkbox-label-${id}`">
      {{ $props.label }}
    </label>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, ref } from 'vue'
import { useElementRef } from '@/hooks'
import useMDCCheckbox from '@/hooks/mdc/useMDCCheckbox'
import uniqueHash from '@/utils/uniqueHash'

export default defineComponent({
  props: {
    label: { type: String, default: 'This is the label' },
    checked: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
  },
  emits: ['update:checked'],
  setup(props, { emit }) {
    const id = uniqueHash()

    const [elRef, setElRef] = useElementRef()
    const MDCCheckboxRef = useMDCCheckbox(elRef)

    const checked = ref(props.checked)

    const handleChange = () => {
      if (MDCCheckboxRef.value) {
        checked.value = MDCCheckboxRef.value.checked
      }
    }

    watch(
      () => props.checked,
      (value) => {
        checked.value = value
        if (MDCCheckboxRef.value) MDCCheckboxRef.value.checked = value
      }
    )

    watch(
      () => props.disabled,
      (value) => {
        if (MDCCheckboxRef.value) {
          MDCCheckboxRef.value.disabled = value
          // MDCCheckboxRef.value.indeterminate = value
        }
      }
    )

    watch(MDCCheckboxRef, (MDCCheckbox) => {
      if (MDCCheckbox) {
        MDCCheckbox.checked = checked.value
        MDCCheckbox.disabled = props.disabled
        // MDCCheckbox.indeterminate = props.disabled
      }
    })

    watch(checked, (value) => emit('update:checked', value))

    return { checked, id, setElRef, handleChange }
  },
})
</script>

<style lang="scss" scoped>
.checkbox__container {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  &.disabled {
    cursor: not-allowed;
    .label {
      cursor: not-allowed;
    }
  }
  .label {
    user-select: none;
  }
}
</style>
