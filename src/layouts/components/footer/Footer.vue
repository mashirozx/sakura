<template>
  <div class="footer__container">
    <div class="row__wrapper">
      <div class="sakura-icon__wrapper fa-spin">
        <ui-icon name="sakura"></ui-icon>
      </div>
    </div>
    <div class="row__wrapper">
      <span v-html="$props.footer1"></span>
    </div>
    <div class="row__wrapper">
      <span v-html="$props.footer2"></span>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'

export default defineComponent({
  props: {
    footer1: {
      type: String,
      default: 'Crafted with <i class="fas fa-heart" style="color: #e74c3c;"></i> by Mashiro',
    },
    footer2: { type: String, default: '© 2021 Mashiro' },
  },
  setup() {
    const name = 'Mashiro'
    return { name }
  },
})
</script>

<style lang="scss" scoped>
.footer__container {
  width: 100%;
  padding: 36px 0 24px 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 12px;
  .sakura-icon__wrapper {
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
}
</style>
