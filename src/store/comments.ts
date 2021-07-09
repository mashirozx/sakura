import { Ref } from 'vue'
import { useState, usePersistedState } from '@/hooks'
import { AxiosResponse } from 'axios' // interface
import { cloneDeep } from 'lodash'
import API from '@/api'
import { GetCommentParams } from '@/api/Wp/v2' // interface
import { getPagination } from '@/utils/filters/paginationFilter'
import logger from '@/utils/logger'

interface FetchParams {
  state: Ref<CommentStore>
  namespace: string
  opts: GetCommentParams
}

export default function comments(): object {
  const defaultCommentStore: CommentStore = {}
  const [commentStore, setCommentStore]: [Ref<CommentStore>, (arg: CommentStore) => void] =
    usePersistedState('commentStore', defaultCommentStore)

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

  const fetchComment = async ({ state, namespace, opts }: FetchParams) => {
    return new Promise((resolve, reject) => {
      API.Wp.v2
        .getComments(opts as GetCommentParams)
        .then((res) => {
          resHandler(state, namespace, res)
          resolve(res)
        })
        .catch((error) => {
          logger('error', error)
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
