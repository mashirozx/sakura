import { computed, onMounted, Ref, nextTick } from 'vue'
import { useInjector, useState, useIntl, useRoute } from '@/hooks'
import { posts } from '@/store'
import { GetPostParams, GetPageParams } from '@/api/Wp/v2' // interfaces
import postFilter from '@/utils/filters/postFilter'

export default function setup(props: {
  readonly singleType?: string | undefined
  readonly pageType?: string | undefined
}) {
  const intl = useIntl()
  const route = useRoute()
  const [fetchStatus, setFetchStatus] = useState('fetching')
  const [content, setContent]: [Ref<Post>, (attr: any) => any] = useState(null)
  const {
    postsStore,
    fetchPost,
    fetchPage,
    getPostsList,
  }: { postsStore: Ref<PostStore>; [key: string]: any } = useInjector(posts) // TODO: fix useInjector return type

  const { slug, postId, postname } = route.params
  const isSingle = props.singleType ? true : false
  const namespace = isSingle ? `single-${postId || postname}` : `page-${slug}`

  // get parsed post content
  const data = computed(() =>
    content.value ? postFilter(content.value, isSingle ? 'single' : 'page') : null
  )

  const defaultFetchOpts: GetPostParams | GetPageParams = { page: 1, perPage: 1 }

  if (postId) {
    defaultFetchOpts['include'] = Number(postId)
  } else if (postname) {
    defaultFetchOpts['slug'] = postname
  } else if (slug) {
    defaultFetchOpts['slug'] = slug
  } else if (isSingle) {
    throw new Error(
      intl.formatMessage(
        {
          id: 'messages.wordpress.permalink.shouldIncludeFieldsInSingle',
          defaultMessage:
            'WordPress permalink should include at least one of %post_id%, %postname%.\nYou may set them here: {baseUrl}/wp-admin/options-permalink.php',
        },
        {
          baseUrl: window.location.origin,
        }
      )
    )
  } else {
    throw new Error(
      intl.formatMessage({
        id: 'messages.wordpress.permalink.shouldIncludeFieldsInPost',
        defaultMessage: 'WordPress pages should use %slug% as the permalink.',
      })
    )
  }

  const fetchContent = () => {
    const fetchOption = isSingle ? fetchPost : fetchPage
    setFetchStatus('fetching')
    fetchOption({
      state: postsStore,
      namespace,
      opts: { ...defaultFetchOpts },
    }).then(() => {
      getContent()
      setFetchStatus('done')
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
      // setFetchStatus('refreshing')
      // TODO: use a transparent mask (or just a popup) to show: 'refeshing content', when it fails or timeout, show popup. If the postsStore is empty, show BookLoader. In other words, BookLoader should only be displayed when real fetching API.
    })
    fetchContent()
  })

  return {
    postData: data,
    postFetchStatus: fetchStatus,
  }
}
