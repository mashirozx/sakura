<template>
  <div v-if="$props.url === null">
    <slot></slot>
  </div>
  <router-link v-else-if="to" :to="to">
    <slot></slot>
  </router-link>
  <a v-else href="https://google.com" target="_blank">
    <slot></slot>
  </a>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import type { RouteLocationRaw } from 'vue-router'
import type { RouterLinkTo } from './types'
import linkHandler from '@/utils/linkHandler'

export default defineComponent({
  props: {
    url: String,
    routerName: String,
    routerParams: Object,
    routerPath: String,
    routerQuery: Object,
    to: Object,
  },
  setup(props) {
    const to = computed(() => {
      if (props.to) return props.to as RouteLocationRaw
      const _to: RouterLinkTo = {}
      if (props.url && linkHandler.isInternal(props.url || '')) {
        _to['path'] = linkHandler.internalLinkRouterPath(props.url)
        return _to
      } else if (props.routerName || props.routerPath) {
        if (props.routerName) {
          _to['name'] = props.routerName
        } else {
          _to['path'] = props.routerPath
          _to['params'] = props.routerParams ?? undefined
        }
        _to['query'] = props.routerQuery ?? undefined
        return _to
      } else {
        return false
      }
    })

    return { to }
  },
})
</script>
