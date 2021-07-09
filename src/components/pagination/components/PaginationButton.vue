<template>
  <div class="button__wrapper" :ref="setTarget">
    <NormalButton :context="context" :outlined="$props.outlined"></NormalButton>
  </div>
</template>

<script lang="ts">
import { defineComponent, reactive, computed } from 'vue'
import { useMouseInElement } from '@vueuse/core'
import { useElementRef } from '@/hooks'
import NormalButton from '@/components/buttons/NormalButton.vue'

export default defineComponent({
  components: { NormalButton },
  props: {
    outlined: Boolean,
    context: String,
    hoverContext: String,
  },
  setup(props) {
    const [target, setTarget] = useElementRef()
    const mouse = reactive(useMouseInElement(target))
    const isOutside = computed(() => mouse.isOutside)
    const context = computed(() => {
      if (!props.hoverContext) return props.context
      if (!isOutside.value) return props.hoverContext
      return props.context
    })

    return { setTarget, context }
  },
})
</script>

<style lang="scss" scoped>
.button__wrapper {
  ::v-deep() {
    .mdc-button {
      height: 28px;
      line-height: 28px;
      min-width: 30px;
      padding: 4px;
      .mdc-button__label {
        color: var(--label-color, var(--mdc-theme-primary, #6200ee));
      }
    }
  }
}
</style>
