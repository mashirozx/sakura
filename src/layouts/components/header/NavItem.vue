<template>
  <div class="nav-item__container mdc-list-item" :ref="setContainerRef" @click="handleClickEvent">
    <div class="mdc-list-item__ripple"></div>
    <span class="nav-item__content mdc-list-item__text">
      <span class="icon icon--prefix" v-if="prefix">
        <i :class="prefix"></i>
      </span>
      <span class="context">{{ context }}</span>
      <span class="icon icon--suffix" v-if="suffix">
        <i :class="suffix"></i>
      </span>
    </span>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useMDCRipple, useRouter, useElementRef } from '@/hooks'
import linkHandler from '@/utils/linkHandler'
export default defineComponent({
  name: 'NavItem',
  props: {
    prefix: String,
    suffix: String,
    context: String,
    url: String,
  },
  setup(props) {
    const [containerRef, setContainerRef] = useElementRef()

    useMDCRipple(containerRef)

    const router = useRouter()

    const handleClickEvent = () => {
      if (props.url) {
        linkHandler.handleClickLink({ url: props.url, router, target: '_blank' })
      }
    }

    return { setContainerRef, handleClickEvent }
  },
})
</script>

<style lang="scss" scoped>
.nav-item__container {
  height: 100%;
  width: 100%;
  cursor: pointer;
  position: relative;
  &.mdc-list-item {
    padding-left: 0;
    padding-right: 0;
  }
  .nav-item__content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-flow: row nowrap;
    justify-content: center;
    align-items: center;
    padding: 0 24px;
    span {
      color: #5f6368;
      font-weight: 500;
      white-space: nowrap;
    }
    .icon {
      &--prefix {
        padding-right: 12px;
      }
      &--suffix {
        padding-left: 12px;
      }
    }
  }
}
</style>
