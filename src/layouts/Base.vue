<template>
  <div class="page">
    <section class="header__wrapper">
      <Header></Header>
    </section>
    <section class="main__wrapper">
      <slot></slot>
    </section>
    <section class="footer__wrapper">
      <Footer></Footer>
    </section>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Header from '@/layouts/components/header/Header.vue'
import Footer from '@/layouts/components/footer/Footer.vue'

export default defineComponent({
  name: 'LayoutBase',
  components: { Header, Footer },
})
</script>

<style lang="scss" scoped>
.page {
  position: relative;
  .header__wrapper {
    position: var(--header-position, sticky);
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1;
  }
  .main__wrapper {
    position: relative;
    z-index: 0;
  }
}
</style>
