<template>
  <section class="selection__container">
    <div class="checkbox__wrapper" v-for="(option, index) in $props.options" :key="index">
      <RadioButton
        v-model:checked="arrayRef[index]"
        :label="option.label"
        :disabled="option.disabled"
      ></RadioButton>
    </div>
  </section>
</template>

<script lang="ts">
import { defineComponent, ref, watch, computed, Ref } from 'vue'
import { cloneDeep } from 'lodash'
import RadioButton from './RadioButton.vue'

export default defineComponent({
  components: { RadioButton },
  props: {
    result: { type: Number, default: () => NaN },
    options: {
      type: Array,
      default: () => [
        { label: 'op 1', disabled: false },
        { label: 'op 2', disabled: false },
        { label: 'op 3', disabled: false },
      ],
    },
  },
  emits: ['update:result'],
  setup(props, { emit }) {
    const arrayRef: Ref<boolean[]> = ref(
      Array(props.options.length)
        .fill(false)
        .map((item, index) => index === props.result)
    )

    // watcher's bug on deep mode: https://github.com/vuejs/vue/issues/2164
    const cacheArrayRef = computed(() => cloneDeep(arrayRef.value))

    watch(
      cacheArrayRef,
      (n, o) => {
        if (n.indexOf(true) < 0) return
        n.forEach((_n, index) => {
          if (_n && _n !== o[index]) {
            const a = cloneDeep(arrayRef.value)
            a.fill(false)
            a[index] = true
            arrayRef.value = a
          }
        })
      },
      { deep: true }
    )

    watch(
      arrayRef,
      (arr) => {
        const value = arr.indexOf(true)
        if (value > -1) {
          emit('update:result', value)
        } else {
          emit('update:result', NaN)
        }
      },
      { deep: true }
    )

    watch(
      () => props.options,
      (options) =>
        (arrayRef.value = Array(options.length)
          .fill(false)
          .map((item, index) => index === props.result))
    )

    watch(
      () => props.result,
      (resultProp) => {
        arrayRef.value = cloneDeep(arrayRef.value).map((item) => false)
        if (resultProp !== NaN) arrayRef.value[resultProp] = true
      }
    )

    return { arrayRef }
  },
})
</script>
