<template>
  <div class="footer__container">
    <div class="footer__content">
      <div class="row__wrapper">
        <div class="sakura-icon__wrapper fa-spin">
          <ui-icon name="ic.sakura"></ui-icon>
        </div>
      </div>
      <div class="row__wrapper">
        <span v-html="$props.footer1"></span>
      </div>
      <div class="row__wrapper">
        <span v-html="$props.footer2"></span>
      </div>
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
@use '@/styles/mixins/polyfills';
.footer__container {
  width: 100%;
  padding: 36px 0 24px 0;
  > .footer__content {
    width: 100%;
    display: flex;
    flex-flow: column nowrap;
    justify-content: center;
    align-items: center;
    @include polyfills.flex-gap(12px, 'column nowrap');
    .sakura-icon__wrapper {
      width: 30px;
      height: 30px;
      display: flex;
      justify-content: center;
      align-items: center;
    }
  }
}
</style>
