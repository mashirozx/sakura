<template>
  <div class="messages__container" :style="positionControl">
    <transition-group name="messages" tag="div">
      <div class="message__wrapper" v-for="message in messagesCalc" :key="message.id">
        <MessageNormal v-if="message.style === 'normal'" :message="message"></MessageNormal>
      </div>
    </transition-group>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed } from 'vue'
import { cloneDeep } from 'lodash'
import { useInjector } from '@/hooks'
import { messages } from '@/store'
import MessageNormal from './MessageNormal.vue'

export default defineComponent({
  components: { MessageNormal },
  props: {
    positionX: { type: String, default: 'right' }, // left center right
    positionY: { type: String, default: 'top' }, // top bottom
    // width: { type: String, default: '380px' },
  },
  setup(props) {
    const { messageList } = useInjector(messages)

    const messagesCalc = computed(() => {
      if (props.positionY === 'bottom') {
        return cloneDeep(messageList.value).reverse()
      } else {
        return cloneDeep(messageList.value)
      }
    })

    const positionControl = computed(() => {
      return {
        '--from-0': props.positionY === 'bottom' ? '100%' : '-100%',
        '--from-70': props.positionY === 'bottom' ? '-20px' : '20px',
        '--from-100': 0,
        '--to-0': 0,
        '--to-30': props.positionX === 'right' ? '-20px' : '20px',
        '--to-70': props.positionX === 'right' ? '100%' : '-100%',
        '--to-100': props.positionX === 'right' ? '100%' : '-100%',
        '--absolute-fix': props.positionY === 'bottom' ? '-100%' : '0',
        // '--width': props.width,
      }
    })

    return { messagesCalc, positionControl }
  },
})
</script>

<style lang="scss" scoped>
.messages__container {
  --msg-width: var(--message-width, 380px);
  @media screen and (max-width: 500px) {
    --msg-width: var(--message-width, 300px);
  }
  @media screen and (max-width: 400px) {
    --msg-width: var(--message-width, 250px);
  }
  @media screen and (max-width: 360px) {
    --msg-width: var(--message-width, 80vw);
  }
  width: calc(var(--msg-width) + 12px);
  .message__wrapper {
    padding: 6px;
  }

  .messages {
    &-enter-active {
      animation: from 0.5s forwards;
    }
    &-leave-active {
      position: absolute;
      transform-origin: center center;
      animation: to 0.5s forwards;
    }
    &-move {
      transition: transform 0.3s ease;
      transition-delay: 0.3s;
    }
  }

  @keyframes from {
    0% {
      transform: translateY(var(--from-0));
      opacity: 0;
    }
    70% {
      transform: translateY(var(--from-70));
      opacity: 0.8;
    }
    100% {
      transform: translateY(var(--from-100));
      opacity: 1;
    }
  }

  @keyframes to {
    0% {
      transform: translateX(var(--to-0)) translateY(var(--absolute-fix));
      opacity: 1;
    }
    30% {
      transform: translateX(var(--to-30)) translateY(var(--absolute-fix));
      opacity: 0.8;
    }
    70% {
      transform: translateX(var(--to-70)) translateY(var(--absolute-fix));
      opacity: 0;
    }
    100% {
      transform: translateX(var(--to-100)) translateY(var(--absolute-fix));
      opacity: 0;
    }
  }
}
</style>
