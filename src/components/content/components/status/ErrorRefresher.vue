<template>
  <div class="error-refresher__wrapper">
    <div class="button" @click="handleRefreshEvent">
      <i class="fas fa-sync-alt"></i>
    </div>
    <div class="desc">
      {{ msg }}
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import { useIntl } from '@/hooks'

export default defineComponent({
  emits: ['refresh'],
  setup(props, { emit }) {
    const intl = useIntl()
    const msg = intl.formatMessage({
      id: 'messages.content.status.errorLoadingComponent',
      defaultMessage: 'Something went wrong when loading this component.',
    })

    const handleRefreshEvent = () => emit('refresh', true)

    return { msg, handleRefreshEvent }
  },
})
</script>

<style lang="scss" scoped>
.error-refresher__wrapper {
  width: 100%;
  display: flex;
  flex-flow: column nowrap;
  align-items: center;
  .button {
    font-size: 64px;
    width: 80px;
    height: 80px;
    line-height: 64px;
    color: #5d5d5d;
    cursor: pointer;
  }
  .desc {
    padding-top: 12px;
    font-size: 15px;
    font-weight: 400;
    color: #5d5d5d;
    text-align: center;
  }
}
</style>
