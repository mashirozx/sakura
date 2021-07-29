import type { Ref } from 'vue'
import { usePersistedState, useState } from '@/hooks'
import type { MessageOptions } from '@/store/messages'
import camelcaseKeys from 'camelcase-keys'
import { AxiosResponse } from 'axios' // interface
import { cloneDeep } from 'lodash'
import API from '@/api'
import { GetPostParams, GetPageParams } from '@/api/Wp/v2' // interface
import { getPagination } from '@/utils/filters/paginationFilter'
import logger from '@/utils/logger'
import axiosErrorHandler from '@/utils/axiosErrorHandler'
import intl from '@/locales'

export interface FetchParams {
  state: Ref<PostStore>
  namespace: string
  opts: GetPostParams | GetPageParams
  addMessage: (options: MessageOptions) => void
}

export default function posts(): object {
  const defaultPostList = {
    idList: [],
    pagination: { page: NaN, perPage: NaN, totalPage: NaN, totalCount: NaN },
    defaultOrder: [],
  }
  const defaultStore: PostStore = {
    data: {},
    list: {},
  }
  const [postsStore, setPostsStore] = false
    ? usePersistedState('postsStore', defaultStore)
    : useState(defaultStore)

  /**
   * Common method of handling API response of Array(WP_POST)
   * @param state postStore
   * @param namespace string
   * @param res axios response
   */
  const resHandler = (
    state: FetchParams['state'],
    namespace: FetchParams['namespace'],
    res: AxiosResponse<any>
  ) => {
    const pagination = getPagination(res)
    const stateCopy = cloneDeep(state.value) as PostStore
    if (!Object.prototype.hasOwnProperty.call(stateCopy.list, namespace)) {
      stateCopy.list[namespace] = defaultPostList
    }
    const _postDataState = stateCopy.data
    const _postListState = stateCopy.list[namespace]

    const startOffset = (pagination.page - 1) * pagination.perPage // (1-1)*10=0
    const endOffset = pagination.page * pagination.perPage - 1 // 1*10-1=9
    let defaultOrder = _postListState.defaultOrder

    // [0,1]=> [0,1,2,3,4,5] i=5 concat( Array(i-length=3) )
    const gap = endOffset - defaultOrder.length + 1
    if (gap > 0) {
      const contactArray = Array(gap).fill(NaN)
      defaultOrder = defaultOrder.concat(contactArray)
    }

    const resPostsList = res.data as [Post]

    let offset = startOffset
    resPostsList.forEach((post) => {
      const index = offset++
      // it's vue3's bug that in v-for="(key, index) in [object]" loop, will return object.id as index
      // post['vIndex'] = index
      defaultOrder[index] = post.id
      const newPost = camelcaseKeys(post) as Post
      const oldPost = _postDataState[post.id]
      if (_postDataState[post.id]) {
        // if post already cached, only update it with existing props
        _postDataState[post.id] = Object.assign(oldPost, newPost)
      } else {
        // create new record
        _postDataState[post.id] = newPost
      }
    })

    // order list by default order
    const orderedPostIdList: number[] = []
    defaultOrder.forEach((id, index) => {
      if (id) {
        orderedPostIdList[index] = id
      }
    })

    stateCopy.data = _postDataState
    _postListState.idList = orderedPostIdList
    _postListState.pagination = pagination
    _postListState.defaultOrder = defaultOrder

    setPostsStore(stateCopy)
  }

  /**
   * Fetch posts list from API /wp-json/wp/v2/posts
   * TODO: what's the correct type of readonly (state)?
   */
  const fetchPost = async ({ state, namespace, opts, addMessage }: FetchParams) => {
    return new Promise((resolve, reject) => {
      API.Wp.v2
        .getPosts(opts as GetPostParams)
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

  /**
   * Fetch posts list from API /wp-json/wp/v2/posts
   * TODO: what's the correct type of readonly (state)?
   */
  const fetchPage = async ({ state, namespace, opts, addMessage }: FetchParams) => {
    return new Promise((resolve, reject) => {
      API.Wp.v2
        .getPages(opts as GetPageParams)
        .then((res) => {
          resHandler(state, namespace, res)
          resolve(res)
        })
        .catch((error) => {
          logger('error', error)
          const errorMsgTitle = intl.formatMessage({
            id: 'messages.posts.fetchPageError',
            defaultMessage: 'Failed to fetch page content.',
          })
          const errorMsg = axiosErrorHandler(error).msg
          addMessage({ type: 'error', title: errorMsgTitle, detail: errorMsg, closeTimeout: 0 })
          reject(error)
        })
    })
  }

  /**
   * Get post list from state, actually this is a static method
   * As a common method, we use the same function to get content of single, page and any other content get from WP_POST
   * @param object { state: Ref<PostStore>, namespace: string, start: number, end: number }
   * @returns
   */
  const getPostsList = ({
    state,
    namespace,
    start,
    end,
  }: {
    state: Ref<PostStore>
    namespace: string
    start: number
    end: number
  }): { idList: number[]; data: Post[] } => {
    if (!state.value.list[namespace]) {
      return { idList: [], data: [] }
    }
    const idList: number[] = state.value.list[namespace].idList.slice(start, end + 1)
    const data: Post[] = []
    idList.forEach((id, index) => (data[index] = state.value.data[id]))

    return { idList, data }
  }

  return { postsStore, fetchPost, getPostsList, fetchPage }
}
