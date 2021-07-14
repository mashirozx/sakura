<template>
  <div class="option__container">
    <h1 class="mdc-typography--headline6">{{ $props.namespace }}</h1>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useInjector } from '@/hooks'
import store from './store'

const defaultOption = {}

export default defineComponent({
  props: {
    options: { type: Object, default: () => defaultOption },
  },
  emits: [],
  setup(props, { emit }) {
    const { config, setConfig } = useInjector(store)
    return {}
  },
})
</script>
