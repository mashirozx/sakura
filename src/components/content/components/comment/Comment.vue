<template>
  <div class="comment__container">
    <h1>Comments</h1>
    <div class="comment-list__wripper">
      <CommentList
        :data="commentData"
        :page="page"
        :perPage="perPage"
        :totalPage="totalPage"
      ></CommentList>
    </div>
    <div class="pagination__wrapper">
      <Pagination
        :current="page"
        :total="totalPage"
        @change:current="handlePageChangeEvent"
        v-if="totalPage > 1"
      ></Pagination>
    </div>
    <div class="composer__wrapper">
      <Composer ref="composerRef" @submit="handleSubmitCommentEvent"></Composer>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, watch, computed, toRefs, onMounted, nextTick, ref } from 'vue'
import { cloneDeep } from 'lodash'
import camelcaseKeys from 'camelcase-keys'
import { useInjector, useState, useRoute } from '@/hooks'
import { comments } from '@/store'
import API from '@/api'
import CommentList from './CommentList.vue'
import Pagination from '@/components/pagination/Pagination.vue'
import Composer from './Composer.vue'

export default defineComponent({
  components: { CommentList, Pagination, Composer },
  props: {
    postId: Number,
  },
  setup(props) {
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
    const [commentData, setCommentData] = useState([])

    const namespace = computed(() => `comment-for-post-${postId.value}`)

    const fetchComments = (page: number, perPage: number) => {
      fetchComment({
        state: commentStore,
        namespace: namespace.value,
        opts: { post: postId.value, page, perPage },
      }).then(() => {
        getComments(page)
      })
    }

    const getComments = (page: number) => {
      const newData = getCommentList({ state: commentStore, namespace: namespace.value, page })

      if (!newData) return

      setCommentData(newData.data)
      setPage(page)
      setPerpage(newData.pagination.perPage)
      setTotalPage(newData.pagination.totalPage)
    }

    // const [composerRef,setComposerRef]=useElementRef()
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
          const _commentData = cloneDeep(commentData.value)
          _commentData.push(camelcaseKeys(res.data))
          setCommentData(_commentData)
          console.log(res.data, commentData.value)
          composerRef.value?.clearInputContent()
        })
        .catch((error) => {
          if (error.response) {
            console.error(error.response)
          } else {
            console.error(error)
          }
        })
    }

    const handlePageChangeEvent = (page: number) => {
      getComments(page)
      fetchComments(page, perPage.value)
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

    return {
      postId,
      page,
      setPage,
      perPage,
      totalPage,
      commentData,
      handlePageChangeEvent,
      handleSubmitCommentEvent,
      composerRef,
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
    padding-top: 12px;
    width: 100%;
  }
  .pagination__wrapper {
    padding-top: 12px;
    align-self: center;
  }
  .composer__wrapper {
    width: 100%;
  }
}
</style>
