<template>
  <div class="post-thumb-list__container" :ref="setListContainerRef">
    <div class="post-thumb-card__wrapper" v-for="(post, index) in postList" :key="index">
      <!-- <span>{{ index }}: {{ post.id }}</span> -->
      <PostThumbCardIndex
        :post="post"
        :type="index % 2 ? 'normal' : 'reverse'"
      ></PostThumbCardIndex>
    </div>
    <div class="loader__wrapper" v-show="fetchStatus === 'pending'">
      <BookLoader></BookLoader>
    </div>
    <div class="last-page__wrapper" v-show="isTheLastPage">{{ lastPageMessage }}</div>
    <div class="navigation-bar__wrapper" v-show="!$props.autoLoad">
      <Pagination
        :current="currentPage"
        :total="totalPage"
        @change:current="handlePageChangeEvent"
        v-if="totalPage > 1"
      ></Pagination>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, onMounted, onBeforeMount } from 'vue'
import type { Ref } from 'vue'
import {
  useInjector,
  useState,
  useElementRef,
  useReachElementSide,
  useMessage,
  useIntl,
} from '@/hooks'
import { posts } from '@/store'
import PostThumbCardIndex from '@/components/cards/postThumbCards/PostThumbCardIndex.vue'
import BookLoader from '@/components/loader/BookLoader.vue'
import Pagination from '@/components/pagination/Pagination.vue'

export default defineComponent({
  components: { PostThumbCardIndex, BookLoader, Pagination },
  props: {
    namespace: { type: String, default: 'homepage' },
    page: { type: Number, default: 1 },
    perPage: { type: Number, default: 10 },
    autoLoad: { type: Boolean, default: true },
    fetchParameters: {
      type: Object,
      default: () => {
        return {}
      },
    },
  },
  setup(props) {
    const addMessage = useMessage()
    const intl = useIntl()
    const [listContainerRef, setListContainerRef] = useElementRef()
    const [fetchStatus, setFetchStatus] = useState('inite' as FetchingStatus)
    const [currentPage, setCurrentPage] = useState(props.page)
    const [currentPageCached, setCurrentPageCached] = useState(props.page) // used to calculate isLastPage
    const [postList, setPostList]: [Ref<Post[]>, (attr: any) => any] = useState([] as Post[])
    const {
      postsStore,
      fetchPost,
      getPostsList,
    }: { postsStore: Ref<PostStore>; [key: string]: any } = useInjector(posts) // TODO: fix useInjector return type

    const start = computed(() => (props.autoLoad ? 0 : (currentPage.value - 1) * props.perPage))
    const end = computed(() => currentPage.value * props.perPage - 1)
    const totalPage = computed(() =>
      postsStore.value.list[props.namespace]
        ? postsStore.value.list[props.namespace].pagination.totalPage
        : NaN
    )

    const get = () => {
      const newPostList = getPostsList({
        state: postsStore,
        namespace: props.namespace,
        start: start.value,
        end: end.value,
      })
      setPostList(newPostList.data)
    }

    const fetch = async () => {
      if (fetchStatus.value !== 'cached') {
        setFetchStatus('pending')
      }
      await fetchPost({
        state: postsStore,
        namespace: props.namespace,
        opts: {
          page: currentPage.value,
          perPage: props.perPage,
          context: 'embed',
          ...props.fetchParameters,
        },
        addMessage,
      })
        .then(() => {
          get()
          setFetchStatus('success')
        })
        .catch(() => {
          setFetchStatus('error')
        })
    }

    const next = () => {
      if (currentPage.value + 1 <= totalPage.value) {
        setCurrentPageCached(currentPage.value)
        setCurrentPage(currentPage.value + 1)
        fetch().then(() => setCurrentPageCached(currentPage.value))
      }
    }
    const prev = () => {
      if (currentPage.value - 1 > 0) {
        setCurrentPageCached(currentPage.value)
        setCurrentPage(currentPage.value - 1)
        fetch().then(() => setCurrentPageCached(currentPage.value))
      }
    }
    const goto = (page: number, reset = true) => {
      if (page <= totalPage.value && page > 0) {
        if (reset) setPostList([] as Post[])
        window.scrollTo({
          top: window.innerHeight * 0.8, // TODO: get cover height
          behavior: 'smooth',
        })
        setCurrentPageCached(currentPage.value)
        setCurrentPage(page)
        fetch().then(() => setCurrentPageCached(currentPage.value))
      }
    }

    const isTheLastPage = computed(() => currentPageCached.value === totalPage.value)

    const handlePageChangeEvent = (page: number) => {
      goto(page)
    }

    useReachElementSide(listContainerRef, {
      bottom: () => props.autoLoad && next(),
    })

    onMounted(() => {
      // this will only work when set to cache post store
      window.setTimeout(() => {
        get()
        if (postList.value.length > 0) {
          setFetchStatus('cached')
          const msg = intl.formatMessage({
            id: 'messages.postList.cache.found',
            defaultMessage: 'Fetching the latest post list...',
          })
          addMessage({
            type: 'info',
            title: msg,
          })
        }
      }, 0) // postsStore injection may not be OK when mounted
      fetch()
    })

    // TODO: not working
    // onMounted(() => window.scrollTo({ top: 0, behavior: 'smooth' }))

    const lastPageMessage = intl.formatMessage({
      id: 'messages.postList.reachLastPage',
      defaultMessage: 'No more...',
      // 很高兴你翻到这里，但是真的没有了...
    })

    return {
      setListContainerRef,
      postList,
      fetchStatus,
      isTheLastPage,
      currentPage,
      totalPage,
      handlePageChangeEvent,
      lastPageMessage,
    }
  },
})
</script>

<style lang="scss" scoped>
.post-thumb-list__container {
  width: 100%;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  align-items: center;
  > *:last-child {
    padding-bottom: 24px;
  }
  > .post-thumb-card__wrapper {
    width: auto;
    padding-top: 24px; // TODO
  }
  > .loader__wrapper {
    padding-top: 24px;
  }
  > .last-page__wrapper {
    color: #989898;
    font-size: 15px;
    padding: 50px 0 20px 0;
  }
  > .navigation-bar__wrapper {
    width: 100%;
  }
}
</style>
