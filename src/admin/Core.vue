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
        <div class="tab-page__content">
          <h1 class="row__wrapper--title">{{ options[tabKey].title }}</h1>
          <p class="row__wrapper--desc" v-if="options[tabKey].desc" v-html="options[tabKey].desc">
          </p>
          <transition-group name="row__wrapper--options">
            <div
              class="option__wrapper"
              v-for="(option, optionIndex) in options[tabKey].options"
              :key="optionIndex"
              v-show="shouldOptionShow(option)"
            >
              <OptionItem :option="option"></OptionItem>
            </div>
          </transition-group>
        </div>
      </SwiperSlide>
    </Swiper>
    <div class="buttons__wrapper">
      <NormalButton
        :icon="['fas', saving ? 'fa-spinner fa-spin' : 'fa-save'].join(' ')"
        context="Save"
        :contained="true"
        @click="handleSaveEvent"
      ></NormalButton>
      <NormalButton icon="fas fa-upload" context="Import" :contained="true"></NormalButton>
      <NormalButton icon="fas fa-download" context="Export" :contained="true"></NormalButton>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, Ref, watch, onMounted, onBeforeUnmount } from 'vue'
import { Swiper, SwiperSlide } from 'swiper/vue'
import { Swiper as SwiperInterface } from 'swiper'
import { useInjector, useState, useMessage } from '@/hooks'
import store from './store'
import options from './options'
import type { Option } from './options'
import API from './api'
import TabBar from '@/components/tabBar/TabBar.vue'
import OptionItem from './OptionItem.vue'
import NormalButton from '@/components/buttons/NormalButton.vue'

export default defineComponent({
  components: { TabBar, Swiper, SwiperSlide, OptionItem, NormalButton },
  setup() {
    // UI controllers
    const tabKeys = Object.keys(options)
    const tabs = tabKeys.map((key) => {
      return { context: options[key].title, icon: options[key].icon, key }
    })

    let defaultCurrentTabIndex: number = 0
    if (window.location.hash) {
      const locationHashMatch = window.location.hash.match(/^#(.*)/)
      if (locationHashMatch && locationHashMatch[1] && tabKeys.indexOf(locationHashMatch[1]) > -1) {
        defaultCurrentTabIndex = tabKeys.indexOf(locationHashMatch[1])
      }
    }
    const currentTabIndex: Ref<number> = ref(defaultCurrentTabIndex)
    const swiperRef: Ref<SwiperInterface | null> = ref(null)

    const handleSwiperEvent = (swiper: SwiperInterface) => {
      swiperRef.value = swiper
      swiper.slideTo(currentTabIndex.value)
    }

    watch(currentTabIndex, (current) => {
      swiperRef.value?.slideTo(current)
      window.location.hash = `#${tabs[current].key}`
    })

    const updateAutoHeight = (timeout = 0) => swiperRef.value?.updateAutoHeight(timeout)

    // auto update height
    onMounted(() => {
      const timer = setInterval(() => updateAutoHeight(100), 100)
      onBeforeUnmount(() => clearInterval(timer))
    })

    // messages
    const addMessage = useMessage()

    // data controllers
    const [saving, setSaving] = useState(false)
    const { config } = useInjector(store)

    const handleSaveEvent = () => {
      if (saving.value) return
      setSaving(true)
      API.postConfigJson(config.value)
        .then((res) => {
          setSaving(false)
          addMessage({
            title: res.data.message,
            type: 'success',
          })
        })
        .catch((error) => {
          setSaving(false)
          console.error(error)
          addMessage({
            title: error.toString(),
            type: 'error',
          })
        })
    }

    const shouldOptionShow = (option: Option) => {
      if (option.depends) {
        return option.depends(config)
      } else {
        return true
      }
    }

    return {
      currentTabIndex,
      tabKeys,
      tabs,
      options,
      handleSwiperEvent,
      handleSaveEvent,
      shouldOptionShow,
      saving,
    }
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
        .row__wrapper {
          &--options {
            &-enter-active {
              transform-origin: top;
              animation: from 0.3s forwards;
            }
            &-leave-active {
              transform-origin: top;
              animation: to 0.3s forwards;
            }
            &-move {
              transition: transform 0.3s ease;
            }
          }
        }
        @keyframes from {
          0% {
            transform: scaleY(0);
            opacity: 0;
          }
          100% {
            transform: scaleY(1);
            opacity: 1;
          }
        }

        @keyframes to {
          0% {
            height: 56px;
            transform: scaleY(1);
            opacity: 1;
          }
          100% {
            height: 0;
            transform: scaleY(0);
            opacity: 0;
          }
        }
      }
    }
  }
  > .buttons__wrapper {
    display: flex;
    flex-flow: row wrap;
    justify-content: flex-start;
    align-items: center;
    gap: 12px;
    padding: 12px;
    width: calc(100% - 24px);
  }
}
</style>
