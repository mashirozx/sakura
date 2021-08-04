import { Ref } from 'vue'
import { useState, usePersistedState } from '@/hooks'
import type { MessageOptions } from '@/store/messages'
import { AxiosResponse } from 'axios' // interface
import { cloneDeep } from 'lodash'
import API from '@/api'
import { GetCommentParams } from '@/api/Wp/v2' // interface
import { getPagination } from '@/utils/filters/paginationFilter'
import logger from '@/utils/logger'
import axiosErrorHandler from '@/utils/axiosErrorHandler'
import intl from '@/locales'

interface FetchParams {
  state: Ref<CommentStore>
  namespace: string
  opts: GetCommentParams
  addMessage: (options: MessageOptions) => void
}

export default function comments(): object {
  const defaultCommentStore: CommentStore = {}
  const [commentStore, setCommentStore] = false
    ? usePersistedState('commentStore', defaultCommentStore)
    : useState(defaultCommentStore)

  const resHandler = (
    state: FetchParams['state'],
    namespace: FetchParams['namespace'],
    res: AxiosResponse<Comment[]>
  ) => {
    const pagination = getPagination(res)
    const stateCopy = cloneDeep(state.value) as CommentStore

    if (!Object.hasOwnProperty.call(stateCopy, namespace)) {
      stateCopy[namespace] = {
        paged: {},
        pagination,
      }
    }

    stateCopy[namespace].pagination = pagination

    res.data.forEach((comment, index) => {
      if (!Object.hasOwnProperty.call(stateCopy[namespace].paged, pagination.page)) {
        stateCopy[namespace].paged[pagination.page] = []
      }
      stateCopy[namespace].paged[pagination.page][index] = comment
    })

    setCommentStore(stateCopy)
  }

  const fetchComment = async ({ state, namespace, opts, addMessage }: FetchParams) => {
    return new Promise((resolve, reject) => {
      API.Wp.v2
        .getComments(opts as GetCommentParams)
        .then((res) => {
          resHandler(state, namespace, res)
          resolve(res)
        })
        .catch((error) => {
          logger('error', error)
          const errorMsgTitle = intl.formatMessage({
            id: 'messages.posts.fetchPostError',
            defaultMessage: 'Failed to fetch post content.',
          })
          const errorMsg = axiosErrorHandler(error).msg
          addMessage({ type: 'error', title: errorMsgTitle, detail: errorMsg, closeTimeout: 0 })
          reject(error)
        })
    })
  }

  const getCommentList = ({
    state,
    namespace,
    page,
  }: {
    state: FetchParams['state']
    namespace: FetchParams['namespace']
    page: number
  }) => {
    if (!state.value[namespace]) return null
    return {
      data: state.value[namespace].paged[page],
      pagination: state.value[namespace].pagination,
    }
  }

  return { commentStore, fetchComment, getCommentList }
}
