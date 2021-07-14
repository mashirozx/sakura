<template>
  <div class="layout mdc-card mdc-card--outlined">
    <div class="tab-bar__wrapper">
      <TabBar v-model:current="currentTabIndex" :items="tabs"></TabBar>
    </div>
    <Swiper
      class="tab-page__wrapper"
      :slidesPerView="1"
      :spaceBetween="50"
      :allowTouchMove="false"
      :autoHeight="true"
      @swiper="handleSwiperEvent"
    >
      <SwiperSlide
        class="tab-page__container"
        v-for="(tabKey, tabKeyIndex) in tabKeys"
        :key="tabKeyIndex"
      >
        <div class="tab-page__content mdc-typography">
          <h1 class="mdc-typography--headline5">{{ options[tabKey].title }}</h1>
          <div
            class="row__wrapper--options"
            v-for="(option, optionIndex) in options[tabKey].options"
            :key="optionIndex"
          >
            {{ option.namespace }}
          </div>
        </div>
      </SwiperSlide>
    </Swiper>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, Ref, watch, nextTick } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Swiper as SwiperInterface } from 'swiper'
import { useInjector } from '@/hooks'
import store from './store'
import options from '@/admin/options'
import TabBar from '@/components/tabBar/TabBar.vue'

export default defineComponent({
  components: { TabBar, Swiper, SwiperSlide },
  setup() {
    // UI controllers
    const currentTabIndex: Ref<number> = ref(0)
    const swiperRef: Ref<SwiperInterface | null> = ref(null)
    const tabKeys = Object.keys(options)
    const tabs = tabKeys.map((key) => {
      return { context: options[key].title, icon: options[key].icon }
    })
    const handleSwiperEvent = (swiper: SwiperInterface) => {
      swiperRef.value = swiper
    }

    watch(currentTabIndex, (current) => swiperRef.value?.slideTo(current))
    nextTick(() => swiperRef.value?.updateAutoHeight(100))

    // data controllers
    const { config, setConfig } = useInjector(store)

    return { currentTabIndex, tabKeys, tabs, options, handleSwiperEvent }
  },
})
</script>

<style lang="scss" scoped>
::v-deep() {
  @import 'swiper/swiper';
}

.layout {
  width: 100%;
  overflow: hidden;
  > .tab-bar__wrapper {
    width: 100%;
    border-bottom: 1px solid rgba(0, 0, 0, 0.12);
  }
  > .tab-page__wrapper {
    width: 100%;
    .tab-page__container {
      width: 100%;
      .tab-page__content {
        width: calc(100% - 24px);
        padding: 12px;
      }
    }
  }
}
</style>
