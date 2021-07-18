<template>
  <header class="header__container mdc-elevation--z4">
    <div class="header__content">
      <div class="logo__wrapper">
        <img class="logo" :src="logo" alt="logo" @load="computeShouldHideNavItemList" />
      </div>
      <div class="nav__wrapper" :ref="setNavBarWrapperRef" @resize="handleNavBarWrapperResizeEvent">
        <div class="nav__ul nav__ul--parent" :ref="setNavBarItemRefs">
          <div
            :class="['nav__li', 'nav__li--parent', { hide: shouldHideNavItemList[parentIndex] }]"
            v-for="(parent, parentIndex) in navItems"
            :key="parentIndex"
          >
            <NavItem :context="parent.title" :prefix="parent.icon" :url="parent.url"></NavItem>
            <div class="drop-down__wrapper">
              <div class="nav__ul nav__ul--child mdc-elevation--z8" v-if="parent.child.length > 0">
                <div
                  :class="['nav__li', 'nav__li--child']"
                  v-for="(child, childIndex) in parent.child"
                  :key="childIndex"
                >
                  <NavItem :context="child.title" :prefix="child.icon" :url="child.url"></NavItem>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div
          class="more__wrapper"
          :ref="setMoreWrapperRef"
          v-show="shouldHideNavItemList.indexOf(true) >= 0"
        >
          <NavItem class="more" context="More" suffix="fas fa-caret-down"></NavItem>
          <div class="drop-down__wrapper">
            <div class="nav__ul nav__ul--child mdc-elevation--z8">
              <div
                :class="[
                  'nav__li',
                  'nav__li--child',
                  { hide: !shouldHideNavItemList[parentIndex] },
                ]"
                v-for="(parent, parentIndex) in navItems"
                :key="parentIndex"
              >
                <NavItem :context="parent.title" :prefix="parent.icon" :url="parent.url"></NavItem>
                <div
                  class="nav__ul nav__ul--grandchild mdc-elevation--z8"
                  v-if="parent.child.length > 0"
                >
                  <div
                    class="nav__li nav__li--grandchild"
                    v-for="(child, childIndex) in parent.child"
                    :key="childIndex"
                  >
                    <NavItem :context="child.title" :prefix="child.icon" :url="child.url"></NavItem>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="profile__wrapper">
        <img class="avatar" :src="avatar" alt="avatar" />
      </div>
    </div>
  </header>
</template>

<script lang="ts">
import { defineComponent, ref, watch, computed } from 'vue'
import {
  useElementRef,
  useElementRefs,
  useResizeObserver,
  useInjector,
  useMDCRipple,
} from '@/hooks'
import { init } from '@/store'
import camelcaseKeys from 'camelcase-keys'
import NavItem from '@/layouts/components/header/NavItem.vue'
import { debounce, cloneDeep } from 'lodash'

export default defineComponent({
  name: 'Header',
  components: { NavItem },
  setup() {
    const avatar = 'https://view.moezx.cc/images/2021/06/13/d6b010a378d392d4633008b915f98ab1.md.png'
    const logo =
      window.InitState.config['basic.site.logo'][0]?.url || 'https://v3.vuejs.org/logo.png'

    const [navBarItemRefs, setNavBarItemRefs] = useElementRefs()
    const [navBarWrapperRef, setNavBarWrapperRef] = useElementRef()
    const [moreWrapperRef, setMoreWrapperRef] = useElementRef()

    const shouldHideNavItemList = ref(Array(navBarItemRefs.value.length).fill(false))

    const computeShouldHideNavItemList = () => {
      const navBarWrapperWidth = navBarWrapperRef.value ? navBarWrapperRef.value.clientWidth : 0
      const moreWrapperWidth = moreWrapperRef.value ? moreWrapperRef.value.clientWidth : 0
      const maxNavItemSumWidth = navBarWrapperWidth - moreWrapperWidth - 24 * 2

      const navItemWidthList = navBarItemRefs.value.map((itemRef) => itemRef?.clientWidth ?? 0)

      let sum = 0
      shouldHideNavItemList.value = navItemWidthList.map((itemWidth) => {
        sum += itemWidth || 0
        return sum >= maxNavItemSumWidth
      })
    }

    const navBarWrapperSize = useResizeObserver(navBarWrapperRef)

    watch(navBarWrapperSize, () => {
      debounce(computeShouldHideNavItemList, 100)()
    })

    const { initState } = useInjector(init)

    const navItems = computed(() => {
      const items: any = []
      const origin = camelcaseKeys(initState.value.menus)['headerMenu'] as Array<any>
      origin.forEach((parent) => {
        if (parent.parent === 0) {
          const item = cloneDeep(parent)
          item['child'] = []
          origin.forEach((child) => {
            if (child.parent === parent.id) {
              item['child'].push(child)
            }
          })
          items.push(item)
        }
      })
      return items
    })

    return {
      navItems,
      setNavBarWrapperRef,
      setNavBarItemRefs,
      setMoreWrapperRef,
      shouldHideNavItemList,
      computeShouldHideNavItemList,
      avatar,
      logo,
      initState,
      useMDCRipple,
    }
  },
})
</script>

<style lang="scss" scoped>
@use "sass:math";
.header__container {
  position: relative;
  width: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #ffffff;
  > .header__content {
    width: calc(100% - 48px);
    height: 48px;
    display: flex;
    flex-flow: row nowrap;
    > .logo__wrapper {
      flex: 0 0 auto;
      display: flex;
      justify-content: center;
      align-items: center;
      > .logo {
        height: 32px;
      }
    }
    > .nav__wrapper {
      flex: 1 1 auto;
      width: 100%;
      display: flex;
      flex-flow: row nowrap;
      padding: 0 24px;
      .nav__ul {
        &.hide {
          position: absolute;
          height: 0;
          visibility: hidden;
          pointer-events: none;
        }
        .nav__li {
          height: 48px;
          &.hide {
            position: absolute;
            height: 0;
            visibility: hidden;
            pointer-events: none;
          }
        }
      }
      // first level (row) (> only real menu)
      > .nav__ul--parent {
        flex: 0 0 auto;
        height: 100%;
        display: flex;
        flex-flow: row nowrap;
        justify-content: flex-start;
        > .nav__li--parent {
          position: relative;
          display: flex;
          justify-content: center;
          align-items: center;
        }
      }
      // second level (column) (common)
      .drop-down__wrapper {
        position: absolute;
        top: 48px;
        left: 50%;
        width: auto;
        min-width: 100%;
        z-index: -1;
        pointer-events: none;
        visibility: hidden;
        transform: translate(-50%, -100%);
        transition: all 0.2s ease-in-out;
        .nav__ul--child {
          position: relative;
          padding: 16px 0;
          min-width: 100%;
          background: #ffffff;
          border-radius: 0 0 5px 5px;
          > .nav__li--child {
            display: flex;
            flex-flow: row nowrap;
            justify-content: flex-start;
            align-items: center;
            position: relative;
            > .nav__ul--grandchild {
              position: absolute;
              top: 0;
              left: 0;
              z-index: -1;
              padding: 16px 0;
              transform: translate(-100%, -16px) scale(0, 0);
              transform-origin: right (16px + math.div(48px, 2));
              background: #ffffff;
              border-radius: 5px;
              width: auto;
              transition: all 0.2s ease-in-out;
              .nav__li--grandchild {
                display: flex;
                flex-flow: row nowrap;
                justify-content: flex-start;
                align-items: center;
              }
            }
            &:hover {
              > .nav__ul--grandchild {
                transform: translate(-100%, -16px) scale(1, 1);
              }
            }
          }
        }
      }
      > .more__wrapper {
        flex: 0 0 auto;
        margin: 0;
        padding: 0 24px 0 0;
        color: #5f6368;
        font-weight: 500;
        display: flex;
        justify-content: center;
        align-items: center;
        position: relative;
        cursor: pointer;
        .more {
          padding-right: 12px;
          ::v-deep(.icon--suffix i) {
            transform: rotate(0deg);
            transition: all 0.2s;
          }
          &:hover {
            ::v-deep(.icon--suffix i) {
              transform: rotate(-180deg);
            }
          }
        }
        .drop-down__wrapper {
          left: calc(50% - 12px);
        }
      }
      > .nav__ul--parent > .nav__li--parent,
      > .more__wrapper {
        &:hover .drop-down__wrapper {
          pointer-events: all;
          cursor: pointer;
          visibility: visible;
          transform: translate(-50%, 0%);
        }
      }
    }

    > .profile__wrapper {
      flex: 0 0 auto;
      display: flex;
      justify-content: center;
      align-items: center;
      > .avatar {
        height: 32px;
        width: 32px;
        object-fit: cover;
        border-radius: 50%;
      }
    }
  }
}
</style>
