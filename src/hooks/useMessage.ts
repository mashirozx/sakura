import type { Ref } from 'vue'
import { useInjector, useIntl } from '@/hooks'
import { messages } from '@/store'
import type { Message, MessageOptions } from '@/store/messages'

/**
 * deprecated
 */
export interface UseMessageInjecter {
  messageList: Ref<Message[]>
  addMessage: (state: Ref<Message[]>, options: MessageOptions) => void
}

/**
 * @param useMessageInjecter (deprecated)
 * @returns
 */
export default function useMessage(useMessageInjecter?: UseMessageInjecter) {
  const { messageList, addMessage }: UseMessageInjecter = useMessageInjecter
    ? useMessageInjecter
    : useInjector(messages)

  const _addMessage = (options: MessageOptions) => {
    addMessage(messageList, options)
  }

  return _addMessage
}

export const useCommonMessages = () => {
  const intl = useIntl()
  return {
    javascriptErrorTitle: intl.formatMessage({
      id: 'messages.commonMessages.javascriptErrorTitle',
      defaultMessage: 'Opps, something when wrong!',
    }),
  }
}
