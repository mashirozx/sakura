<template>
  <div>
    <!-- PC -->
    <div v-if="!isMobile" class="page">
      <header class="header__wrapper">
        <Header></Header>
      </header>
      <div class="header__placeholder" v-if="$props.headerPlaceholder"></div>
      <section class="content__wrapper">
        <slot></slot>
        <footer class="footer__wrapper">
          <Footer></Footer>
        </footer>
      </section>
    </div>
    <!-- / PC -->
    <!-- Mobile -->
    <div v-else :class="['page', 'mobile', { 'show-drawer': shouldDrawerOpen }]">
      <header class="header__wrapper">
        <HeaderMobile :open="shouldDrawerOpen" @toggle="handleMDrawerToggleEvent"></HeaderMobile>
        <div class="fake-after" @click="handleClickFakeAfterEvent"></div>
      </header>
      <div class="header__placeholder mdc-elevation--z4" v-if="$props.headerPlaceholder"></div>
      <section class="content__wrapper mdc-elevation--z4">
        <slot></slot>
        <footer class="footer__wrapper">
          <Footer></Footer>
        </footer>
        <div class="fake-after" @click="handleClickFakeAfterEvent"></div>
      </section>
      <aside class="drawer__wrapper">
        <NavDrawer></NavDrawer>
      </aside>
    </div>
    <!-- / Mobile -->
    <div class="go-top__wrapper">
      <GoTop :showDrawer="shouldDrawerOpen"></GoTop>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, onUnmounted, onDeactivated } from 'vue'
import { throttle } from 'lodash'
import {
  useState,
  useWindowResize,
  useKeepAliveWindowScrollTop,
  useWindowScrollLock,
} from '@/hooks'
import Header from '@/layouts/components/header/Header.vue'
import Footer from '@/layouts/components/footer/Footer.vue'
import HeaderMobile from '@/layouts/components/header/HeaderMobile.vue'
import NavDrawer from '@/layouts/components/header/NavDrawer.vue'
import GoTop from './components/goTop/GoTop.vue'

export default defineComponent({
  name: 'LayoutBase',
  components: { Header, Footer, HeaderMobile, NavDrawer, GoTop },
  props: { headerPlaceholder: { type: Boolean, default: true } },
  setup() {
    useKeepAliveWindowScrollTop()
    const windowSize = useWindowResize()
    const isMobile = computed(() => windowSize.value.innerWidth <= 600)
    const [shouldDrawerOpen, setShouldDrawerOpen] = useState(false)

    const [removeScrollLock, addScrollLock] = useWindowScrollLock()

    const toggleDrawer = throttle(
      () => {
        setShouldDrawerOpen(!shouldDrawerOpen.value)
        if (shouldDrawerOpen.value) {
          addScrollLock()
        } else {
          removeScrollLock()
        }
      },
      500,
      {
        trailing: false,
      }
    )

    const handleMDrawerToggleEvent = () => {
      toggleDrawer()
    }

    const handleClickFakeAfterEvent = () => {
      toggleDrawer()
    }

    onUnmounted(() => {
      setShouldDrawerOpen(false)
    })

    onDeactivated(() => {
      setShouldDrawerOpen(false)
    })

    return { isMobile, handleMDrawerToggleEvent, shouldDrawerOpen, handleClickFakeAfterEvent }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/app';
::v-deep() {
  @include app.global;
}

$drawer-width: 260px;
.page {
  position: relative;
  overflow: hidden;
  .header__wrapper {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 48px;
    z-index: 2;
  }
  .header__placeholder {
    width: 100%;
    height: 48px;
    visibility: hidden;
  }
  .content__wrapper {
    position: relative;
    z-index: 1;
    overflow: hidden;
    background: #ffffff;
  }
  &.mobile {
    .header__wrapper {
      overflow-x: hidden; // hide box shadow
      height: 60px; // left the gap for box shadow
      ::v-deep() {
        .toggler__wrapper {
          z-index: 4;
        }
      }
    }
    .content__wrapper {
      position: relative;
      left: 0;
      z-index: 1;
    }
    .header__wrapper,
    .content__wrapper {
      transition: transform 0.5s;
      > .fake-after {
        position: absolute;
        top: 0;
        right: 0;
        width: 0;
        height: 0;
        background: rgba(0, 0, 0, 0.2);
        content: '';
        opacity: 0;
        transition: opacity 0.5s, width 0.1s 0.5s, height 0.1s 0.5s;
        z-index: 3;
      }
    }
    .drawer__wrapper {
      position: fixed;
      top: 0;
      left: 0;
      width: #{$drawer-width};
      height: 100%;
      background: #ffffff;
      visibility: hidden;
      transition: all 0.5s;
      z-index: 0;
    }
    &.show-drawer {
      .drawer__wrapper {
        visibility: visible;
        transition: transform 0.5s;
      }
      .content__wrapper,
      .header__wrapper {
        transform: translate3d(#{$drawer-width}, 0, 0);
        > .fake-after {
          width: 100%;
          height: 100%;
          opacity: 1;
          transition: opacity 0.5s;
        }
      }
      .header__wrapper {
        > .fake-after {
          height: 48px;
        }
      }
    }
  }
}
.go-top__wrapper {
  --drawer-width: #{$drawer-width};
}
</style>
