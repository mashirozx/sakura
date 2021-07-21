<template>
  <div class="nav__container mdc-elevation--z4">
    <div class="nav__content">
      <div class="column__wrapper--toggler toggler__wrapper" @click="handleToggleEvent">
        <div :class="['toggler', { active: $props.open }]">
          <span></span>
        </div>
      </div>
      <div class="column__wrapper--logo">
        <div class="logo__wrapper" v-if="logo">
          <img class="logo" :src="logo" alt="logo" draggable="false" />
        </div>
        <div class="sitename" v-if="sitename">{{ sitename }}</div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import sakuraOptions from '@/utils/sakuraOptions'

export default defineComponent({
  props: { open: Boolean },
  emits: ['toggle'],
  setup(props, { emit }) {
    const handleToggleEvent = () => {
      emit('toggle', !props.open)
    }
    const logo = sakuraOptions['basic.site.logo'][0]?.url
    const sitename = sakuraOptions['basic.site.title']

    return { handleToggleEvent, logo, sitename }
  },
})
</script>

<style lang="scss" scoped>
// @use 'sass:math';
.nav__container {
  position: relative;
  width: 100%;
  height: 48px;
  background: #ffffff;
  display: flex;
  align-items: center;
  justify-content: center;
  > .nav__content {
    width: calc(100% - 12px * 2);
    display: flex;
    flex-flow: row nowrap;
    justify-content: space-between;
    align-items: center;
    > .column__wrapper {
      &--toggler {
        $width: 30px;
        $height: 3px;
        $color: var(--toggler-color, #333333);
        > .toggler {
          position: relative;
          width: #{$width};
          height: #{$width};
          cursor: pointer;
          user-select: none;
          display: flex;
          align-items: center;
          justify-content: center;
          > span {
            transition: all 0.2s ease-in-out;
            width: #{$width};
            height: #{$height};
            background: #{$color};
            position: relative;
            display: block;
            &::before {
              transition: all 0.2s ease-in-out;
              content: '';
              display: block;
              background: #{$color};
              height: #{$height};
              width: #{$width};
              position: absolute;
              // top: -16px;
              transform: rotate(0deg);
              transform-origin: 13%;
              top: -8px;
            }
            &::after {
              transition: all 0.2s ease-in-out;
              content: '';
              display: block;
              background: #{$color};
              height: #{$height};
              width: #{$width};
              position: absolute;
              transform: rotate(0deg);
              transform-origin: 13%;
              top: 8px;
            }
          }
          &.active {
            > span {
              transition: all 0.2s ease-in-out;
              background: transparent;
              &::before {
                transition: all 0.2s ease-in-out;
                transform: rotate(45deg);
                // width: #{$width / math.sin(45deg)};
              }
              &::after {
                transition: all 0.2s ease-in-out;
                transform: rotate(-45deg);
                // width: #{$width / math.sin(45deg)};
              }
            }
          }
        }
      }
      &--logo {
        display: flex;
        flex-flow: row-reverse nowrap;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        > .logo__wrapper {
          flex: 0 0 auto;
          display: flex;
          justify-content: center;
          align-items: center;
          > .logo {
            height: 32px;
          }
        }
        > .sitename {
        }
      }
    }
  }
}
</style>
