<template>
  <div class="comment-list__container">
    <transition-group name="comment-list" tag="div">
      <div
        class="item__wrapper"
        v-for="(item, index) in $props.data"
        :key="index + item.id"
        :style="{ '--animation-timeout': `${1 + (item.noDisplayDelay ? 0 : index) * 0.2}s` }"
      >
        <CommentListItem :data="item"></CommentListItem>
      </div>
    </transition-group>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import CommentListItem from './CommentListItem.vue'

export default defineComponent({
  components: { CommentListItem },
  props: { data: Object },
})
</script>

<style lang="scss" scoped>
.comment-list__container {
  .comment-list {
    &-enter-active {
      animation: lightSpeedInLeft /* animate.css */ var(--animation-timeout) ease-in;
    }
    // &-leave-active {
    //   position: absolute;
    //   transform-origin: center center;
    //   animation: lightSpeedOutRight /* animate.css */ 1s ease-in;
    // }
    &-move {
      transition: transform 0.3s ease;
      transition-delay: 0.3s;
    }
  }
  .item__wrapper {
    padding-top: 12px;
    &:last-child {
      padding-bottom: 12px;
    }
  }
}
</style>
