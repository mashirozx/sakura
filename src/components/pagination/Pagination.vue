<template>
  <div class="pagination__container">
    <div class="items__wrapper" v-for="(item, index) in items" :key="index">
      <div
        class="item__wrapper previous"
        v-if="item === -1"
        @click="handleClickEvent($props.current - 1)"
      >
        <PaginationButton context="‹" :outlined="$props.outlined"></PaginationButton>
      </div>
      <div
        class="item__wrapper next"
        v-else-if="item === -2"
        @click="handleClickEvent($props.current + 1)"
      >
        <PaginationButton context="›" :outlined="$props.outlined"></PaginationButton>
      </div>
      <div
        class="item__wrapper ellipsi--previous"
        v-else-if="item === -3"
        @click="handleClickEvent($props.current - 3)"
      >
        <PaginationButton
          context="⋯"
          hoverContext="«"
          :outlined="$props.outlined"
        ></PaginationButton>
      </div>
      <div
        class="item__wrapper ellipsis--next"
        v-else-if="item === -4"
        @click="handleClickEvent($props.current + 3)"
      >
        <PaginationButton
          context="⋯"
          hoverContext="»"
          :outlined="$props.outlined"
        ></PaginationButton>
      </div>
      <div class="item__wrapper current" v-else-if="item === $props.current">
        <PaginationButton :context="item.toString()" :outlined="$props.outlined"></PaginationButton>
      </div>
      <div class="item__wrapper available" v-else @click="handleClickEvent(item)">
        <PaginationButton :context="item.toString()" :outlined="$props.outlined"></PaginationButton>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch } from 'vue'
import PaginationButton from './components/PaginationButton.vue'

export default defineComponent({
  components: { PaginationButton },
  props: {
    current: { type: Number, default: 5 },
    total: { type: Number, default: 10 },
    outlined: { type: Boolean, default: false },
  },
  emits: ['change:current'],
  setup(props, { emit }) {
    const items = computed(() => {
      const { current, total } = props
      const list = [] // -3 for .../«, -4 for .../», -1 for previous, -2 for next

      if (current > 1) list.push(-1)

      //before
      if (current < 5) {
        for (let i = 1; i <= current; i++) list.push(i)
      } else {
        list.push(1)
        list.push(-3)
        list.push(current - 2)
        list.push(current - 1)
        list.push(current)
      }

      //after
      if (total - current + 1 < 5) {
        for (let i = current + 1; i <= total; i++) list.push(i)
      } else {
        list.push(current + 1)
        list.push(current + 2)
        list.push(-4)
        list.push(total)
      }

      if (current < total) list.push(-2)

      return list
    })

    const handleClickEvent = (page: number) => {
      page = Math.max(page, 1)
      page = Math.min(page, props.total)

      emit('change:current', page)
    }
    return { items, handleClickEvent }
  },
})
</script>

<style lang="scss" scoped>
.pagination__container {
  width: 100%;
  display: flex;
  flex-flow: row wrap;
  justify-content: center;
  align-items: center;
  gap: 6px;
  .items__wrapper {
    .item__wrapper {
      --mdc-typography-button-font-size: 13px;
      --mdc-theme-primary: #5f6368;
      &.current {
        --label-color: #1989fa;
      }
    }
  }
}
</style>
