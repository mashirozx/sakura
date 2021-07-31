// declare module 'decamelize-keys' {
//   export default function decamelizeKeys<T>(input: T, separator: string): T
// }

/**
 * Sakura initState
 */
declare var InitState: any

/**
 * reCaptcha
 */
declare var grecaptcha: any

interface Pagination {
  page: number
  perPage: number
  totalPage: number
  totalCount: number
}

interface WPPostAbstract {
  id: number
  date: string
  modified: string
  slug: string
  status: string
  type: string
  link: string
  title: {
    rendered: string
  }
  content: {
    rendered: string
    protected: boolean
    markdown?: string | null
  }
  excerpt: {
    rendered: string
    protected: boolean
    plain: string
  }
  author: number
  authorMeta: object
  featuredMedia: number
  featuredMediaMeta: { [key: string]: any }
  commentStatus: string
  pingStatus: string
  sticky: boolean
  template: string
  format: string
  meta: [any?]
  categories: [number?]
  categoriesMeta: { [key: string]: any }
  tags: [number?]
  tagsMeta: {
    [key: string]: {
      count: number
      description: string
      filter: string
      name: string
      parent: number
      slug: string
      taxonomy: string
      termGroup: number
      termId: number
      termTaxonomyId: number
    }
  }
  commentCount: number
  viewCount: number
  wordsCount: number
  links: { [key: string]: any }
}

interface Post extends WPPostAbstract {
  [key: string]: any
}

interface PostListData {
  [key: number]: Post
}

interface PostStore {
  data: PostListData // where we save all post data (indexed by id)
  list: {
    // the saved list type, ie. 'homepage', 'catName'
    [namespace: string]: {
      idList: number[] // where we save post lists (only ID order)
      pagination: Pagination
      defaultOrder: number[]
    }
  }
}

interface WPCommentAbstract {
  id: number // view, edit, embed
  author: number // view, edit, embed
  authorEmail?: string // edit
  authorIp?: string // edit
  authorName: string // view, edit, embed
  authorUrl: string // view, edit, embed
  authorUserAgent?: string // edit
  content: {
    rendered: string
    markdown?: string | null
  } // view, edit, embed
  date: string // view, edit, embed
  dateGmt?: string // view, edit
  link: string // view, edit, embed
  parent: number // view, edit, embed
  post?: number // view, edit
  status?: string // view, edit
  type: string // view, edit, embed
  authorAvatarUrls: { [key: string]: any } // view, edit, embed
  meta?: { [key: string]: any } // view, edit
}

interface Comment extends WPCommentAbstract {
  ancestor?: number
  metaFields: {
    userAgentInfo: string
    userLocation: string
  }
}

interface CommentStore {
  [namespace: string]: {
    paged: { [page: number]: Comment[] }
    pagination: Pagination
  }
}

declare type FetchingStatus =
  | 'inite'
  | 'cached'
  | 'pending'
  | 'success'
  | 'error'
  | 'empty'
  | 'noMore'
