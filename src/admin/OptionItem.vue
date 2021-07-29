<template>
  <div class="option__container">
    <h3 class="column__wrapper--label">
      {{ title }}
      <span class="restore" :title="msg.restoreTitle" @click="handleRestoreEvent"
        ><i class="fas fa-redo-alt"></i
      ></span>
    </h3>
    <div class="column__wrapper--main">
      <div class="row__wrapper--option">
        <OutlinedInput
          v-if="type === 'string'"
          v-model:content="optionResultRef"
          v-bind="binds"
        ></OutlinedInput>
        <OutlinedTextarea
          v-else-if="type === 'longString'"
          v-model:content="optionResultRef"
          v-bind="binds"
        ></OutlinedTextarea>
        <Selection
          v-else-if="type === 'selection'"
          v-model:result="optionResultRef"
          v-bind="binds"
        ></Selection>
        <Choose
          v-else-if="type === 'choose'"
          v-model:result="optionResultRef"
          v-bind="binds"
        ></Choose>
        <MediaPicker
          v-else-if="type === 'mediaPicker'"
          v-model:selection="optionResultRef"
          v-bind="binds"
        ></MediaPicker>
        <Switcher
          v-else-if="type === 'switcher'"
          v-model:checked="optionResultRef"
          v-bind="binds"
        ></Switcher>
      </div>
      <p class="row__wrapper--desc" v-if="desc">
        <i class="fas fa-info-circle"></i> <span v-html="desc"></span>
      </p>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, watch } from 'vue'
import { cloneDeep } from 'lodash'
import { useInjector, useIntl } from '@/hooks'
import store from './store'
import validator from './validator'
import OutlinedInput from '@/components/inputs/OutlinedInput.vue'
import OutlinedTextarea from '@/components/inputs/OutlinedTextarea.vue'
import Selection from '@/components/checkbox/Selection.vue'
import MediaPicker from '@/admin/components/MediaPicker.vue'
import Choose from '@/components/radio/Choose.vue'
import Switcher from '@/components/switcher/Switcher.vue'

export default defineComponent({
  components: { OutlinedInput, OutlinedTextarea, Selection, MediaPicker, Choose, Switcher },
  props: {
    option: { type: Object, required: true },
  },
  emits: [],
  setup(props, { emit }) {
    const intl = useIntl()
    const msg = {
      restoreTitle: intl.formatMessage({
        id: 'admin.restore.title',
        defaultMessage: 'Restore this option to default.',
      }),
    }
    const { namespace, type, title, desc, binds } = props.option
    const { config, updateOption } = useInjector(store)
    const optionResultRef = ref(config.value[namespace] ?? cloneDeep(props.option).default)

    watch(
      optionResultRef,
      (result) => {
        if (validator(result, type).pass) {
          updateOption(config, namespace, result)
        }
      },
      { immediate: true, deep: true }
    )

    const handleRestoreEvent = () => {
      optionResultRef.value = cloneDeep(props.option).default
    }

    return { msg, config, optionResultRef, type, title, desc, binds, handleRestoreEvent }
  },
})
</script>

<style lang="scss" scoped>
@use './variables';
.option__container {
  display: flex;
  flex-flow: row nowrap;
  align-items: space-between;
  justify-content: flex-start;
  @media screen and (max-width: variables.$mobile-max-width) {
    flex-flow: column nowrap;
    align-items: flex-start;
    justify-content: flex-start;
  }
  > .column__wrapper {
    &--label {
      flex: 0 0 auto;
      width: 200px;
      padding-top: 15px;
      @media screen and (max-width: variables.$mobile-max-width) {
        margin-block-start: 0;
        margin-block-end: 0;
        padding-top: 0;
      }
      > .restore {
        padding-left: 6px;
        font-size: 0.8em;
        color: var(--mdc-theme-secondary, #1d2327);
        :hover {
          color: var(--mdc-theme-primary, #6200ee);
        }
      }
    }
    &--main {
      width: 100%;
      flex: 1 1 auto;
      display: flex;
      flex-flow: column nowrap;
      align-items: flex-start;
      justify-content: flex-start;
      padding-top: 12px;
      > .row__wrapper {
        &--option {
          width: 100%;
        }
        &--desc {
          font-size: 14px;
          color: #646970;
          // margin-block-end: 0;
        }
      }
    }
  }
}

// custom
// ::v-deep() {
// .mdc-checkbox {
//   transform: translateX(-13px);
// }
// }

// Polyfill WP default styles
::v-deep() {
  input.disabled,
  input:disabled,
  select.disabled,
  select:disabled,
  textarea.disabled,
  textarea:disabled {
    background: unset;
    border-color: unset;
    box-shadow: unset;
    color: unset;
  }
  input[type='checkbox'],
  input[type='radio'] {
    border: unset;
  }

  input[type='checkbox']:focus,
  input[type='color']:focus,
  input[type='date']:focus,
  input[type='datetime-local']:focus,
  input[type='datetime']:focus,
  input[type='email']:focus,
  input[type='month']:focus,
  input[type='number']:focus,
  input[type='password']:focus,
  input[type='radio']:focus,
  input[type='search']:focus,
  input[type='tel']:focus,
  input[type='text']:focus,
  input[type='time']:focus,
  input[type='url']:focus,
  input[type='week']:focus,
  select:focus,
  textarea:focus {
    border-color: unset;
    box-shadow: unset;
    outline: unset;
  }

  .notice-error,
  div.error {
    border-left-color: unset;
  }
}
</style>
