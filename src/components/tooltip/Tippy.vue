<template>
  <div class="tooltip__container">
    <div class="tooltip" :ref="setTooltipRef">
      <slot></slot>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, onMounted } from 'vue'
import { useElementRef } from '@/hooks'
import useTippy from '@/hooks/lib/useTippy'

export default defineComponent({
  props: {
    // https://atomiks.github.io/tippyjs/v6/all-props
    content: { type: [String, Object, Function], default: 'Hello World!' },
    allowHTML: { type: Boolean, default: false },
    animateFill: { type: Boolean, default: false },
    animation: { type: String, default: 'fade' },
    appendTo: { type: [String, Object, Function], default: () => document.body },
    aria: {
      type: Object,
      default: () => {
        return {
          content: 'auto',
          expanded: 'auto',
        }
      },
    },
    arrow: { type: [String, Object, Boolean], default: true },
    delay: { type: [Number, Array], default: 0 },
    duration: { type: [Number, Array], default: 0 },
    followCursor: { type: [Boolean, String], default: false },
    getReferenceClientRect: { type: Function },
    hideOnClick: { type: [Boolean, String], default: true },
    plugins: { type: Array, default: () => [] },
    // TODO: much more props... continue: ignoreAttributes
  },
  setup(props) {
    const [tooltipRef, setTooltipRef] = useElementRef()

    const opts: Parameters<typeof useTippy>[1] = {
      content: props.content,
      allowHTML: props.allowHTML,
      appendTo: props.appendTo,
      aria: props.aria,
      arrow: props.arrow,
      delay: props.delay as any,
      duration: props.duration as any,
      getReferenceClientRect: (props.getReferenceClientRect ?? null) as any,
      hideOnClick: props.hideOnClick as any,
    }

    if (props.animateFill) opts['animateFill'] = props.animateFill
    if (props.plugins) opts['plugins'] = props.plugins as any
    if (props.followCursor) opts['followCursor'] = props.followCursor as any

    const tippy = useTippy(tooltipRef, opts)

    return { setTooltipRef }
  },
})
</script>

<style lang="scss" scoped>
.tooltip__container {
  width: 100%;
  height: 100%;
}
</style>
