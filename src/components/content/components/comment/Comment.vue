<template>
  <div class="comment__container">
    <h3 class="comment-list__title" :ref="setCommentListTitleRef">
      <span>{{ msg.comments.heading }}</span>
      <span>{{ msg.comments.commentCount.value }}</span>
      <!-- // TODO: Is it a but that nested reactive value cannot be destructured in template? -->
    </h3>
    <div class="updating-status__wrapper">
      <Toggler :show="shouldShowUpdatingStatus">
        <div class="content__wrapper">
          <div class="content" :data-status="fetchStatus">
            <i :class="updatingLatestIcon"></i>&nbsp;
            {{ updatingLatestMsg }}
          </div>
        </div>
      </Toggler>
    </div>
    <div class="comment-list__wrapper" v-if="commentData.length > 0 || true">
      <CommentList
        :data="commentData"
        :page="page"
        :perPage="perPage"
        :totalPage="totalPage"
      ></CommentList>
    </div>
    <div class="loader__wrapper" v-show="fetchStatus === 'pending'">
      <BookLoader></BookLoader>
    </div>
    <div class="error__wrapper" v-show="fetchStatus === 'error'">
      <ErrorRefresher @refresh="handleRefreshEvent"></ErrorRefresher>
    </div>
    <div class="pagination__wrapper" v-if="totalPage > 1">
      <Pagination
        :current="$props.order === 'desc' ? totalPage - page + 1 : page"
        :total="totalPage"
        @change:current="handlePageChangeEvent"
      ></Pagination>
    </div>
    <div class="composer__wrapper">
      <Composer ref="composerRef" @submit="handleSubmitCommentEvent"></Composer>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, computed, toRefs, onMounted, nextTick, ref } from 'vue'
import type { Ref } from 'vue'
import { cloneDeep } from 'lodash'
import camelcaseKeys from 'camelcase-keys'
import {
  useInjector,
  useState,
  useRoute,
  useMessage,
  useIntl,
  useScrollToElement,
  useElementRef,
} from '@/hooks'
import { comments } from '@/store'
import API from '@/api'
import axiosErrorHandler from '@/utils/axiosErrorHandler'
import CommentList from './CommentList.vue'
import Pagination from '@/components/pagination/Pagination.vue'
import Composer from './Composer.vue'
import ErrorRefresher from '../status/ErrorRefresher.vue'
import BookLoader from '@/components/loader/BookLoader.vue'
import Toggler from '@/components/toggler/Toggler.vue'

export default defineComponent({
  components: { CommentList, Pagination, Composer, BookLoader, Toggler, ErrorRefresher },
  props: {
    postId: Number,
    order: { type: String, default: 'desc' }, // order: 'desc', orderby: 'date_gmt'
    orderby: { type: String, default: 'date_gmt' },
    commentTotalCount: { type: Number, default: 0 },
  },
  setup(props) {
    const addMessage = useMessage()
    const intl = useIntl()
    const route = useRoute()
    // const commentPagination = {
    //   hash: route.hash, // TODO: support nested
    //   page: route.params.commentPage,
    // }

    const [postId, setPostId] = useState(0)
    const { commentStore, fetchComment, getCommentList } = useInjector(comments)
    const [page, setPage] = useState(1)
    const [perPage, setPerpage] = useState(10)
    const [totalPage, setTotalPage] = useState(1)
    const [totalCount, setTotalCount] = useState(props.commentTotalCount)
    const [commentData, setCommentData] = useState([] as Comment[])
    const [fetchStatus, setFetchStatus] = useState('inite' as FetchingStatus)
    const [willGetFromCache, setWillGetFromCache] = useState(false)

    const [CommentListTitleRef, setCommentListTitleRef] = useElementRef()
    const scrollToCommentListWrapperTop = useScrollToElement(
      CommentListTitleRef as Ref<HTMLElement>,
      'top',
      'top'
    )

    const namespace = computed(() => `comment-for-post-${postId.value}`)

    const fetchComments = async (page: number, perPage: number) => {
      if (willGetFromCache.value) {
        setFetchStatus('updating')
      } else {
        setFetchStatus('pending')
      }
      setWillGetFromCache(false)
      fetchComment({
        state: commentStore,
        namespace: namespace.value,
        opts: { post: postId.value, page, perPage, order: props.order, orderby: props.orderby },
        addMessage,
      })
        .then(() => {
          getComments(page)
          setFetchStatus('success')
        })
        .catch(() => {
          setFetchStatus('error')
        })
    }

    const getComments = (page: number, tryToGetFromCache = false) => {
      const newData = getCommentList({ state: commentStore, namespace: namespace.value, page })

      if (!newData?.data) {
        if (tryToGetFromCache) setWillGetFromCache(false)
        setCommentData([])
      } else {
        if (tryToGetFromCache) {
          setWillGetFromCache(true)
        } else {
          setWillGetFromCache(false)
        }
        setCommentData(newData.data)
        setPerpage(newData.pagination.perPage)
        setTotalPage(newData.pagination.totalPage)
        setTotalCount(newData.pagination.totalCount)
      }
      setPage(page)
    }

    const composerRef = ref<InstanceType<typeof Composer>>()

    const createComment = ({
      authorEmail,
      authorName,
      authorUrl,
      content,
    }: {
      [key: string]: string
    }) => {
      const parent = 0
      const post = postId.value
      API.Sakura.v1
        .createComment({ authorEmail, authorName, authorUrl, content, parent, post })
        .then((res) => {
          const _commentData = cloneDeep(commentData.value) as Comment[]
          const response = camelcaseKeys(res.data)
          response['noDisplayDelay'] = true
          _commentData.push(response)
          setCommentData(_commentData)
          setTotalCount(totalCount.value + 1)
          // console.log(res.data, commentData.value)
          addMessage({
            type: 'success',
            title: intl.formatMessage({
              id: 'messages.comment.submit.success',
              defaultMessage: 'Comment post successfully.',
            }),
          })
          composerRef.value?.clearInputContent()
        })
        .catch((error) => {
          const titleMsg = intl.formatMessage({
            id: 'messages.comment.submit.error',
            defaultMessage: 'Comment post failure.',
          })
          const errorMsg = axiosErrorHandler(error).msg
          console.log(errorMsg)
          addMessage({ type: 'error', title: titleMsg, detail: errorMsg, closeTimeout: 0 })
        })
    }

    const handlePageChangeEvent = (page: number) => {
      if (props.order === 'desc') {
        const target = totalPage.value - page + 1
        getComments(target, true)
        fetchComments(target, perPage.value).then(() => setWillGetFromCache(false))
      } else {
        getComments(page, true)
        fetchComments(page, perPage.value).then(() => setWillGetFromCache(false))
      }
      scrollToCommentListWrapperTop()
    }

    const handleSubmitCommentEvent = (event: {
      content: string
      authorName: string
      authorEmail: string
      authorUrl: string
    }) => {
      createComment(event)
    }

    onMounted(() => {
      nextTick(() => {
        setWillGetFromCache(true)
        getComments(page.value)
      })
      watch(
        toRefs(props).postId,
        (id) => {
          if (id) setPostId(id)
          fetchComments(page.value, perPage.value)
        },
        { immediate: true }
      )
    })

    const msg = {
      updatingLatest: {
        updating: intl.formatMessage({
          id: 'messages.commentList.cache.updating',
          defaultMessage: 'Updating the latest comment list...',
        }),
        success: intl.formatMessage({
          id: 'messages.commentList.cache.updateSuccess',
          defaultMessage: 'Comment list updated.',
        }),
        error: intl.formatMessage({
          id: 'messages.commentList.cache.updateError',
          defaultMessage: 'Opps! Something went wrong when updating comment list.',
        }),
      },
      comments: {
        heading: intl.formatMessage({
          id: 'messages.commentList.title.heading',
          defaultMessage: 'Comments',
        }),
        commentCount: computed(() =>
          intl.formatMessage(
            {
              id: 'messages.commentList.title.commentCount',
              defaultMessage:
                '{commentCount, plural, =0 {Be the first one to leave a comment!} =1 {One comment} other {{commentCount, number, ::compact-short} Comments}}',
            },
            { commentCount: totalCount.value }
          )
        ),
      },
    }

    const updatingLatestMsg = computed(() => {
      if (Object.hasOwnProperty.call(msg.updatingLatest, fetchStatus.value)) {
        return msg.updatingLatest[fetchStatus.value as keyof typeof msg.updatingLatest]
      }
    })
    const updatingLatestIcon = computed(() => {
      switch (fetchStatus.value) {
        case 'updating':
          return 'fas fa-sync fa-spin'
        case 'success':
          return 'fas fa-check'
        case 'error':
          return 'fas fa-times'
        default:
          return ''
      }
    })

    const [shouldShowUpdatingStatus, setShouldShowUpdatingStatus] = useState(false)
    watch(fetchStatus, (value) => {
      if (value === 'updating') {
        setShouldShowUpdatingStatus(true)
      } else {
        window.setTimeout(() => setShouldShowUpdatingStatus(false), 1000)
      }
    })

    const handleRefreshEvent = () => {
      setWillGetFromCache(false)
      fetchComments(page.value, perPage.value)
    }

    watch(
      () => props.commentTotalCount,
      (value) => {
        setTotalCount(value)
      }
    )

    return {
      commentStore, // debug
      msg,
      fetchStatus,
      postId,
      page,
      setPage,
      perPage,
      totalPage,
      commentData,
      handlePageChangeEvent,
      handleSubmitCommentEvent,
      composerRef,
      setCommentListTitleRef,
      updatingLatestMsg,
      updatingLatestIcon,
      shouldShowUpdatingStatus,
      handleRefreshEvent,
    }
  },
})
</script>

<style lang="scss" scoped>
.comment__container {
  width: 100%;
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  > * {
    padding: 12px 0;
    width: 100%;
  }
  .error__wrapper {
    width: 100%;
  }
  .pagination__wrapper {
    align-self: center;
  }
  .composer__wrapper {
    width: 100%;
  }
  .comment-list__title {
    padding-top: 0;
    width: 100%;
    margin: 0 auto;
    color: #7d7d7d;
    font-weight: 400;
    span {
      &:first-child {
        padding-right: 6px;
        &::after {
          content: '|';
          padding-left: 6px;
        }
      }
      // &:last-child {
      //   padding-left: 6px;
      // }
    }
  }
  .comment-list__wrapper {
    padding: 0;
  }
  .updating-status__wrapper {
    width: 100%;
    padding: 0;
    .content__wrapper {
      width: 100%;
      .content {
        width: calc(100% - 24px);
        padding: 12px;
        border-radius: 4px;
        text-align: center;
        background: transparent;
        color: transparent;
        transition: all 0.2s;
        &[data-status='updating'] {
          background: #39c0ed;
          color: #ffffff;
        }
        &[data-status='success'] {
          background: #acda78;
          color: #ffffff;
        }
        &[data-status='error'] {
          background: #f93154;
          color: #ffffff;
        }
      }
    }
  }
}
</style>
