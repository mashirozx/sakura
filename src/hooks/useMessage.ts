import type { Ref } from 'vue'
import { useInjector } from '@/hooks'
import { messages } from '@/store'
import type { Message, MessageOptions } from '@/store/messages'

export default function useMessage() {
  const {
    messageList,
    addMessage,
  }: {
    messageList: Ref<Message[]>
    addMessage: (state: Ref<Message[]>, options: MessageOptions) => void
  } = useInjector(messages)

  const _addMessage = (options: MessageOptions) => {
    addMessage(messageList, options)
  }

  return _addMessage
}
