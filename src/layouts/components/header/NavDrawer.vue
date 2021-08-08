<template>
  <div class="drawer__container" :ref="setScrollContainerRef">
    <div class="drawer__content">
      <div class="row__wrapper--avatar">
        <div class="image__wrapper">
          <Image
            src="https://view.moezx.cc/images/2021/06/13/d6b010a378d392d4633008b915f98ab1.md.png"
            placeholder=""
            :avatar="true"
            alt=""
            :draggable="false"
          ></Image>
        </div>
      </div>
      <div class="row__wrapper--signature">Hello world...</div>
      <div class="row__wrapper--social"> social</div>
      <div class="row__wrapper--search">
        <div class="background">
          <input class="input" type="search" name="search" placeholder="Search..." required="" />
        </div>
      </div>
      <div class="row__wrapper--menu">
        <div
          :class="['ul__wrapper', { active: currentActive === parentIndex }]"
          v-for="(parent, parentIndex) in navItems"
          :key="parentIndex"
        >
          <div class="ul__content--tag" @click="handleClickParentEvent($event, parentIndex)">
            <NavItem
              :context="parent.title"
              :prefix="parent.icon"
              :url="parent.child.length > 0 ? null : parent.url"
              :suffix="parent.child.length > 0 ? 'fas fa-chevron-down' : ''"
            ></NavItem>
          </div>
          <div class="ul__content--child">
            <div class="li__wrapper" v-for="(child, childIndex) in parent.child" :key="childIndex">
              <div class="li__content--tag">
                <NavItem :context="child.title" :prefix="child.icon" :url="child.url"></NavItem>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref } from 'vue'
import camelcaseKeys from 'camelcase-keys'
import { cloneDeep } from 'lodash'
import { useElementRef, useInjector } from '@/hooks'
import usePerfectScrollbar from '@/hooks/lib/usePerfectScrollbar'
import { init } from '@/store'
import NavItem from '@/layouts/components/header/NavItem.vue'

export default defineComponent({
  components: { NavItem },
  setup() {
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

    const [scrollContainerRef, setScrollContainerRef] = useElementRef()
    const ps = usePerfectScrollbar(scrollContainerRef, { suppressScrollX: true })

    const currentActive = ref(NaN)
    const total = computed(() => navItems.value.length)

    const changeCurrentActive = (i: number) => {
      if (i === currentActive.value) currentActive.value = NaN
      else if (i < total.value && i >= 0) currentActive.value = i
    }

    const handleClickParentEvent = (event: Event, i: number) => {
      // if has child
      if (navItems.value[i].child.length > 0) {
        changeCurrentActive(i)
        const collapseContainer = (event.currentTarget as HTMLElement).parentElement
        const collapseContent = (event.currentTarget as HTMLElement)?.nextElementSibling
        if (collapseContent instanceof HTMLElement) {
          collapseContainer?.style.setProperty(
            '--collapse-height',
            `${collapseContent.scrollHeight}px`
          )
        }
      }
    }

    return {
      navItems,
      setScrollContainerRef,
      currentActive,
      handleClickParentEvent,
    }
  },
})
</script>

<style lang="scss" scoped>
@use '@/styles/mixins/polyfills';
.drawer__container {
  position: relative;
  width: 100%;
  height: 100%;
  > .drawer__content {
    width: 100%;
    display: flex;
    flex-flow: column nowrap;
    justify-content: flex-start;
    align-items: center;
    @include polyfills.flex-gap(24px, 'column nowrap');
    > .row__wrapper {
      &--avatar {
        flex: 0 0 auto;
        .image__wrapper {
          margin-top: 50px;
          width: 90px;
          height: 90px;
          border-radius: 50%;
          overflow: hidden;
        }
      }
      &--signature {
        text-align: center;
        color: #333333;
        font-weight: 900;
        font-family: sans-serif;
        letter-spacing: 1.5px;
      }
      // &--social {
      // }
      &--search {
        width: 100%;
        .background {
          width: 100%;
          border-top: 1px solid rgba(153, 153, 153, 0.3);
          border-bottom: 1px solid rgba(153, 153, 153, 0.3);
          .input {
            width: 100%;
            border: unset;
            padding: 8px 12px 8px 30px;
            outline: none;
            color: #666666;
            font-size: 16px;
          }
        }
      }
      &--menu {
        width: 100%;
        > .ul__wrapper {
          width: 100%;
          > .ul__content {
            &--tag {
              width: 100%;
              height: 36px;
              background: rgba(2, 1, 1, 0);
              transition: all 0.3s;
              ::v-deep() {
                .nav-item__content {
                  .icon--suffix {
                    transform: scale(0.6);
                    transform-origin: right;
                    i {
                      transform: rotate(0deg);
                      transition: all 0.2s;
                    }
                  }
                }
              }
            }
            &--child {
              max-height: 0;
              overflow: hidden;
              background: rgba(95, 93, 93, 0);
              transition: all 0.3s;
              > .li__wrapper {
                > .li__content--tag {
                  width: 100%;
                  height: 36px;
                }
              }
            }
          }
          &.active {
            .ul__content {
              &--tag {
                background: rgba(2, 1, 1, 0.05);
                ::v-deep() {
                  .nav-item__content {
                    .icon--suffix {
                      i {
                        transform: rotate(-180deg);
                      }
                    }
                  }
                }
              }
              &--child {
                max-height: var(--collapse-height);
                background: rgba(95, 93, 93, 0.15);
              }
            }
          }
        }
        ::v-deep() {
          .nav-item__content {
            justify-content: space-between;
            .context {
              flex: 1 1 auto;
              width: 100%;
            }
          }
        }
      }
    }
  }
}
</style>
