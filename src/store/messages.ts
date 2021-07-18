import { Ref } from 'vue'
import { cloneDeep, remove } from 'lodash'
import { useState } from '@/hooks'
import uniqueHash from '@/utils/uniqueHash'

export interface Message {
  id: string
  title: string
  detail?: string
  type?: 'success' | 'warning' | 'info' | 'error'
  style?: 'normal' | 'collapse'
  options?: { [key: string]: any }
  closeTimeout?: number
}

export interface MessageOptions extends Omit<Message, 'id'> {}

export default function msg(): object {
  const [messageList, setMessageList]: [Ref<Message[]>, (arg: Message[]) => void] = useState([])

  const addMessage = (state: typeof messageList, options: MessageOptions) => {
    const id = `message_${uniqueHash()}`
    const _state = cloneDeep(state.value)
    const message = { ...options, id }
    message['type'] ||= 'info' // the default message type
    message['style'] ||= 'normal' // the default message type
    _state.push(message)
    setMessageList(_state)

    if (options.closeTimeout !== undefined && options.closeTimeout <= 0) {
      return
    }

    const closeTimeout = options.closeTimeout || 3000

    setTimeout(() => removeMessage(state, id), closeTimeout)
  }

  const removeMessage = (state: typeof messageList, id: string) => {
    const _state = cloneDeep(state.value)
    remove(_state, (item) => item.id === id)
    setMessageList(_state)
  }

  const clearMessage = () => {
    setMessageList([])
  }

  return {
    messageList,
    addMessage,
    removeMessage,
    clearMessage,
  }
}
