<template>
  <div class="post-thumb-list__container" :ref="setListContainerRef">
    <div class="post-thumb-card__wrapper" v-for="(post, index) in postList" :key="index">
      <span>{{ index }}: {{ post.id }}</span>
      <PostThumbCardClassic
        :post="post"
        :type="index % 2 ? 'normal' : 'reverse'"
      ></PostThumbCardClassic>
    </div>
    <div class="loader__wrapper" v-show="fetchStatus === 'fetching'">
      <BookLoader></BookLoader>
    </div>
    <div class="last-page__wrapper" v-show="isTheLastPage">no more</div>
    <div class="navigation-bar__wrapper" v-show="!autoLoad">1 2 3 4 5 6 7</div>
  </div>
</template>

<script lang="ts">
import { defineComponent, computed, onMounted, Ref } from 'vue'
import { useInjector, useState, useElementRef, useReachElementSide } from '@/hooks'
import { posts } from '@/store'
import PostThumbCardClassic from '@/components/cards/postThumbCards/PostThumbCardClassic.vue'
import BookLoader from '@/components/loader/BookLoader.vue'

export default defineComponent({
  components: { PostThumbCardClassic, BookLoader },
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
    const [listContainerRef, setListContainerRef] = useElementRef()
    const [fetchStatus, setFetchStatus] = useState('fetching')
    const [currentPage, setCurrentPage] = useState(props.page)
    const [postList, setPostList]: [Ref<Post[]>, (attr: any) => any] = useState([] as Post[])
    const {
      postsStore,
      fetchPost,
      getPostsList,
    }: { postsStore: Ref<PostStore>; [key: string]: any } = useInjector(posts) // TODO: fix useInjector return type

    const start = computed(() => (props.autoLoad ? 0 : (currentPage.value - 1) * props.perPage - 1))
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
      setFetchStatus('fetching')
      fetchPost({
        state: postsStore,
        namespace: props.namespace,
        opts: {
          page: currentPage.value,
          perPage: props.perPage,
          context: 'embed',
          ...props.fetchParameters,
        },
      }).then(() => {
        get()
        setFetchStatus('done')
      })
    }

    const next = () => {
      if (currentPage.value + 1 <= totalPage.value) {
        setCurrentPage(currentPage.value + 1)
        fetch()
      }
    }
    const prev = () => {
      if (currentPage.value - 1 > 0) {
        setCurrentPage(currentPage.value - 1)
        fetch()
      }
    }
    const goto = (page: number) => {
      if (page <= totalPage.value && page > 0) {
        setCurrentPage(page)
        fetch()
      }
    }

    const isTheLastPage = computed(() => currentPage.value === totalPage.value)

    useReachElementSide(listContainerRef, {
      bottom: () => next(),
    })

    onMounted(() => {
      window.setTimeout(() => {
        get()
        // setFetchStatus('done')
        // TODO: use a transparent mask (or just a popup) to show: 'refeshing content', when it fails or timeout, show popup. If the postsStore is empty, show BookLoader. In other words, BookLoader should only be displayed when real fetching API.
      }, 500) // postsStore injection may not be OK when mounted
      fetch()
    })

    return { setListContainerRef, postList, fetchStatus, isTheLastPage }
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
    padding-top: 24px;
  }
  > .loader__wrapper {
    padding-top: 24px;
  }
}
</style>
