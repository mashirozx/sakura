import { computed, onMounted, Ref, nextTick } from 'vue'
import { isEmpty } from 'lodash'
import { useInjector, useState, useIntl, useRoute, useMessage, useCommonMessages } from '@/hooks'
import { posts, messages } from '@/store'
import { GetPostParams, GetPageParams } from '@/api/Wp/v2' // interfaces
import postFilter from '@/utils/filters/postFilter'

export type Content = Post | {}

export default function setup(props: {
  readonly singleType?: string | undefined
  readonly pageType?: string | undefined
}) {
  const intl = useIntl()
  const addMessage = useMessage()
  const commonMessages = useCommonMessages()
  const route = useRoute()
  const [fetchStatus, setFetchStatus] = useState('inite' as FetchingStatus)
  const [content, setContent] = useState({} as Content)
  const {
    postsStore,
    fetchPost,
    fetchPage,
    getPostsList,
  }: { postsStore: Ref<PostStore>; [key: string]: any } = useInjector(posts) // TODO: fix useInjector return type

  // TODO: [bug] https://github.com/mashirozx/sakura-next/issues/148
  // console.log('[DEBUG]', route.params)
  // addMessage({
  //   title: '[DEBUG] contentHandler',
  //   detail: JSON.stringify(route.params),
  //   type: 'info',
  //   closeTimeout: 0,
  // })

  const { slug, postId, postname } = route.params
  const isSingle = props.singleType ? true : false
  const namespace = isSingle ? `single-${postId || postname}` : `page-${slug}`

  // get parsed post content
  const data = computed(() =>
    !isEmpty(content.value) ? postFilter(content.value as Post, isSingle ? 'single' : 'page') : {}
  )

  const defaultFetchOpts: GetPostParams | GetPageParams = { page: 1, perPage: 1 }

  if (postId) {
    defaultFetchOpts['include'] = Number(postId)
  } else if (postname) {
    defaultFetchOpts['slug'] = postname as string
  } else if (slug) {
    defaultFetchOpts['slug'] = slug as string
  } else if (isSingle) {
    // TODO: should wait router https://github.com/mashirozx/sakura-next/issues/148
    const errorMsg = intl.formatMessage(
      {
        id: 'messages.wordpress.permalink.shouldIncludeFieldsInSingle',
        defaultMessage:
          'WordPress permalink should include at least one of %post_id%, %postname%.\nYou may set them here: {baseUrl}/wp-admin/options-permalink.php',
      },
      {
        baseUrl: window.location.origin,
      }
    )
    addMessage({
      title: commonMessages.javascriptErrorTitle,
      detail: errorMsg,
      type: 'error',
      closeTimeout: 0,
    })
    console.error(errorMsg)
  } else {
    const errorMsg = intl.formatMessage({
      id: 'messages.wordpress.permalink.shouldIncludeFieldsInPost',
      defaultMessage: 'WordPress pages should use %slug% as the permalink.',
    })
    addMessage({
      title: commonMessages.javascriptErrorTitle,
      detail: errorMsg,
      type: 'error',
      closeTimeout: 0,
    })
    console.error(errorMsg)
  }

  const fetchContent = () => {
    const fetchOption = isSingle ? fetchPost : fetchPage
    if (fetchStatus.value !== 'cached') {
      setFetchStatus('pending')
    }
    fetchOption({
      state: postsStore,
      namespace,
      opts: { ...defaultFetchOpts },
      addMessage,
    })
      .then(() => {
        window.setTimeout(() => {
          getContent()
          setFetchStatus('success')
        }, 500)
      })
      .catch(() => {
        setFetchStatus('error')
      })
  }

  const getContent = () => {
    const newPostList = getPostsList({
      state: postsStore,
      namespace: namespace,
      start: 0,
      end: 1,
    })
    setContent(newPostList.data[0])
  }

  onMounted(() => {
    nextTick(() => {
      getContent()
      if ((content.value as Post)?.content) {
        setFetchStatus('cached')
        const msg = intl.formatMessage({
          id: 'messages.postContent.cache.found',
          defaultMessage: 'Fetching the latest post content...',
        })
        addMessage({
          type: 'info',
          title: msg,
        })
      }
    })
    fetchContent()
  })

  return {
    postData: data,
    postFetchStatus: fetchStatus,
  }
}
