<template>
  <div class="comment-list__container">
    <div class="item__wrapper" v-for="(item, index) in $props.data" :key="index">
      <CommentListItem :data="item"></CommentListItem>
    </div>
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
.item__wrapper {
  &:not(:first-child) {
    padding-top: 12px;
  }
}
</style>
