<template>
  <div class="image__container">
    <img
      :class="['image', state]"
      :src="$props.src"
      :alt="$props.alt"
      :draggable="$props.draggable"
      @error="handleError"
      @load="handleLoad"
    />
    <img v-if="placeholderImage" class="default" :src="placeholderImage" :alt="$props.alt" />
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { useState } from '@/hooks'
import gravatar from '@/utils/gravatar'

export default defineComponent({
  emits: ['error', 'load'],
  props: {
    src: String,
    placeholder: { type: String, default: '' },
    avatar: { type: Boolean, default: false },
    alt: String,
    draggable: { type: Boolean, default: false },
  },
  setup(props, { emit }) {
    const [state, setState] = useState('loading')
    const placeholderImage = computed(() => {
      if (!props.src) {
        setState('error')
        emit('error', new Event('error'))
      }
      if (props.placeholder) return props.placeholder
      if (props.avatar) return gravatar('wapuu@wordpress.example')
    })

    const handleError = (event: Event) => {
      setState('error')
      emit('error', event)
    }

    const handleLoad = (event: Event) => {
      setState('load')
      emit('load', event)
    }

    return {
      placeholderImage,
      handleError,
      handleLoad,
      state,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/skeleton';
.image__container {
  width: 100%;
  height: 100%;
  position: relative;
  @include skeleton.skeleton-loading;
  .image,
  .default {
    width: 100%;
    height: 100%;
    object-fit: var(--object-fit, cover);
  }
  .image {
    position: absolute;
    top: 0;
    left: 0;
    &.error {
      visibility: hidden;
    }
  }
}
</style>
