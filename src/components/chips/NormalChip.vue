<template>
  <div class="mdc-chip" :ref="setSetRef">
    <div class="mdc-chip__ripple"></div>
    <span role="gridcell">
      <span role="button" tabindex="0" class="mdc-chip__primary-action">
        <span class="mdc-chip__text">{{ $props.context }}</span>
      </span>
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useElementRef, useMDCRipple } from '@/hooks'

export default defineComponent({
  props: {
    icon: String,
    context: String,
  },
  setup() {
    const [chipRef, setSetRef] = useElementRef()
    useMDCRipple(chipRef)

    return { setSetRef }
  },
})
</script>
