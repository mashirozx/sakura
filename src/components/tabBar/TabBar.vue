<template>
  <div class="mdc-tab-bar" role="tablist" :ref="setTabBarRef">
    <div class="mdc-tab-scroller">
      <div class="mdc-tab-scroller__scroll-area">
        <div class="mdc-tab-scroller__scroll-content">
          <!-- :class="['mdc-tab', { 'mdc-tab--active': current === index }]" -->
          <button
            v-for="(item, index) in $props.items"
            :key="index"
            class="mdc-tab"
            role="tab"
            aria-selected="true"
            tabindex="index"
            @click="handleChangeIndexEvent(index)"
          >
            <span class="mdc-tab__content">
              <i :class="['mdc-tab__icon', item.icon]" aria-hidden="true"></i>
              <span class="mdc-tab__text-label">{{ item.context }}</span>
            </span>
            <!-- :class="['mdc-tab-indicator', { 'mdc-tab-indicator--active': current === index }]" -->
            <span class="mdc-tab-indicator">
              <span class="mdc-tab-indicator__content mdc-tab-indicator__content--underline"></span>
            </span>
            <span class="mdc-tab__ripple"></span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, onMounted, watch } from 'vue'
import { useState } from '@/hooks'
import { MD5 } from 'crypto-js'
import { useElementRef } from '@/hooks'
import useMDCTabBar from '@/hooks/mdc/useMDCTabBar'

export default defineComponent({
  props: {
    items: {
      type: Array,
      default: () => [
        { context: 'Tab 1', icon: 'fab fa-app-store-ios' },
        { context: 'Tab 2', icon: 'fab fa-app-store-ios' },
        { context: 'Tab 3', icon: 'fab fa-app-store-ios' },
      ],
    },
    current: { type: Number, default: 0 },
  },
  emits: ['update:current'],
  setup(props, { emit }) {
    const [tabBarRef, setTabBarRef] = useElementRef()
    const MDCTabBar = useMDCTabBar(tabBarRef)

    const [current, setCurrent] = useState(props.current)

    watch(current, (value) => emit('update:current', value))
    watch(
      () => props.current,
      (value) => setCurrent(value)
    )

    const handleChangeIndexEvent = (index: number) => {
      setCurrent(index)
      MDCTabBar.value?.scrollIntoView(index)
      MDCTabBar.value?.activateTab(index)
    }

    onMounted(() => {
      MDCTabBar.value?.scrollIntoView(props.current)
      MDCTabBar.value?.activateTab(props.current)
    })

    return { current, handleChangeIndexEvent, setTabBarRef }
  },
})
</script>

<style lang="scss" scoped>
.tab-bar__container {
  width: 100%;
}
.mdc-tab__icon {
  font-size: 14px;
  width: 14px;
  height: 14px;
}
</style>
