<template>
  <div :class="['switcher__container', { disabled: $props.disabled }]">
    <button
      :id="`switch-${id}`"
      class="mdc-switch mdc-switch--unselected"
      role="switch"
      :aria-checked="checked"
      :ref="setElRef"
    >
      <div class="mdc-switch__track"></div>
      <div class="mdc-switch__handle-track">
        <div class="mdc-switch__handle">
          <div class="mdc-switch__shadow">
            <div class="mdc-elevation-overlay"></div>
          </div>
          <div v-show="!$props.disableRipple" class="mdc-switch__ripple"></div>
          <div class="mdc-switch__icons">
            <svg class="mdc-switch__icon mdc-switch__icon--on" viewBox="0 0 24 24">
              <path d="M19.69,5.23L8.96,15.96l-4.23-4.23L2.96,13.5l6,6L21.46,7L19.69,5.23z" />
            </svg>
            <svg class="mdc-switch__icon mdc-switch__icon--off" viewBox="0 0 24 24">
              <path d="M20 13H4v-2h16v2z" />
            </svg>
          </div>
        </div>
      </div>
    </button>
    <label class="label" :for="`switch-${id}`">
      {{ checked ? $props.positiveLabel : $props.negativeLabel }}
      <slot name="label"></slot>
      <slot name="label-positive" v-if="checked"></slot>
      <slot name="label-negative" v-else></slot>
    </label>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import uniqueHash from '@/utils/uniqueHash'
import { useElementRef, useIntervalWatcher } from '@/hooks'
import useMDCSwitch from '@/hooks/mdc/useMDCSwitch'

export default defineComponent({
  props: {
    positiveLabel: { type: String, default: 'current on' },
    negativeLabel: { type: String, default: 'current off' },
    checked: { type: Boolean, default: true },
    disabled: { type: Boolean, default: false },
    disableRipple: { type: Boolean, default: false },
  },
  emits: ['update:checked'],
  setup(props, { emit }) {
    const id = uniqueHash()

    const [elRef, setElRef] = useElementRef()
    const MDCSwitchRef = useMDCSwitch(elRef)

    const checked = ref(props.checked)

    useIntervalWatcher(() => {
      if (MDCSwitchRef.value && MDCSwitchRef.value.selected !== checked.value) {
        checked.value = MDCSwitchRef.value.selected
      }
    }, 100)

    watch(
      () => props.checked,
      (value) => {
        checked.value = value
        if (MDCSwitchRef.value) MDCSwitchRef.value.selected = value
      },
      { immediate: true }
    )

    watch(
      () => props.disabled,
      (value) => {
        if (MDCSwitchRef.value) {
          MDCSwitchRef.value.disabled = value
        }
      }
    )

    watch(MDCSwitchRef, (MDCCheckbox) => {
      if (MDCCheckbox) {
        MDCCheckbox.selected = checked.value
        MDCCheckbox.disabled = props.disabled
      }
    })

    watch(checked, (value) => emit('update:checked', value))

    return { id, setElRef, checked }
  },
})
</script>

<style lang="scss" scoped>
@use './theme';
@use '@/styles/mixins/polyfills';
.switcher__container {
  @include theme.variables;
  height: 56px;
  display: flex;
  flex-flow: var(--flex-flow, row nowrap);
  justify-content: flex-start;
  align-items: center;
  @include polyfills.flex-gap(6px, 'row nowrap');
  &.disabled {
    cursor: not-allowed;
    .label {
      cursor: not-allowed;
    }
  }
  .label {
    user-select: none;
    // padding-left: 10px;
  }
}
</style>
