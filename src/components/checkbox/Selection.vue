<template>
  <section class="selection__container">
    <div class="checkbox__wrapper" v-for="(option, index) in $props.options" :key="index">
      <Checkbox
        v-model:checked="resultRef[index]"
        :label="option.label"
        :disabled="(isMax && !resultRef[index]) || option.disabled"
      ></Checkbox>
    </div>
  </section>
</template>

<script lang="ts">
import { defineComponent, ref, watch, computed } from 'vue'
import Checkbox from './Checkbox.vue'

export default defineComponent({
  components: { Checkbox },
  props: {
    max: { type: Number, default: -1 },
    result: { type: Array, default: () => [true, false, true] },
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
    const resultRef = ref(props.result)

    const isMax = computed(() => {
      const { max } = props
      if (max < 0) {
        return false
      } else {
        const count = resultRef.value.filter((x) => x).length
        return count >= max
      }
    })

    watch(
      () => props.result,
      (resultProp) => {
        resultRef.value = resultProp
      },
      { deep: true }
    )

    return { resultRef, isMax }
  },
})
</script>
