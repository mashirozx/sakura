<template>
  <div class="radio__container">
    <div class="mdc-radio" :ref="setElRef">
      <input
        class="mdc-radio__native-control"
        type="checkbox"
        :id="`radio-${id}`"
        :name="`radio-${id}`"
        @change="handleChange"
      />
      <div class="mdc-radio__background">
        <div class="mdc-radio__outer-circle"></div>
        <div class="mdc-radio__inner-circle"></div>
      </div>
      <div class="mdc-radio__ripple"></div>
    </div>
    <label class="label" :for="`radio-${id}`">{{ $props.label }}</label>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, ref } from 'vue'
import { useElementRef } from '@/hooks'
import uniqueHash from '@/utils/uniqueHash'
import useMDCRadio from '@/hooks/mdc/useMDCRadio'

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

    const MDCRadioRef = useMDCRadio(elRef)

    const checked = ref(props.checked)

    const handleChange = (event: Event) => {
      if (!props.disabled) checked.value = !checked.value
    }

    watch(
      () => props.checked,
      (value) => {
        checked.value = value
        if (MDCRadioRef.value) MDCRadioRef.value.checked = value
      }
    )

    watch(
      () => props.disabled,
      (value) => {
        if (MDCRadioRef.value) {
          MDCRadioRef.value.disabled = value
        }
      }
    )

    watch(MDCRadioRef, (MDCCheckbox) => {
      if (MDCCheckbox) {
        MDCCheckbox.checked = checked.value
        MDCCheckbox.disabled = props.disabled
      }
    })

    watch(checked, (value) => {
      if (MDCRadioRef.value) MDCRadioRef.value.checked = value
      emit('update:checked', value)
    })

    return { checked, id, setElRef, handleChange }
  },
})
</script>

<style lang="scss" scoped>
.radio__container {
  display: flex;
  flex-direction: row;
  justify-content: flex-start;
  align-items: center;
  .label {
    user-select: none;
  }
}
</style>
