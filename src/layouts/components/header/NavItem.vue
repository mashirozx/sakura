<template>
  <Link class="link__container" :url="$props.url">
    <div class="nav-item__container">
      <Ripple>
        <span class="nav-item__content">
          <span class="icon icon--prefix" v-if="prefix">
            <i :class="prefix"></i>
          </span>
          <span class="context">{{ context }}</span>
          <span class="icon icon--suffix" v-if="suffix">
            <i :class="suffix"></i>
          </span>
        </span>
      </Ripple>
    </div>
  </Link>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Ripple from '@/components/ripple/Ripple.vue'

export default defineComponent({
  components: { Ripple },
  props: {
    prefix: String,
    suffix: String,
    context: String,
    url: String,
  },
})
</script>

<style lang="scss" scoped>
.link__container {
  height: 100%;
  width: 100%;
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
      height: 100%;
      display: flex;
      flex-flow: row nowrap;
      justify-content: center;
      align-items: center;
      margin: 0 24px;
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
}
</style>
