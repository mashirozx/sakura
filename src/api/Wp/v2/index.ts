import request, { AxiosPromise } from '@/utils/http'
import snakecaseKeys from 'snakecase-keys'

type WpPostObjectFilter = number | string | number[] | string[]

/**
 * GET /wp/v2/posts
 * https://developer.wordpress.org/rest-api/reference/posts/#arguments
 */
export interface GetPostParams {
  context?: 'view' | 'embed' | 'edit'
  page?: number
  perPage?: number
  search?: string
  after?: string // ISO8601
  author?: string | number
  authorExclude?: string | number
  before?: string // ISO8601
  exclude?: WpPostObjectFilter // TODO: check this
  include?: WpPostObjectFilter // TODO: check this
  offset?: number
  order?: 'asc' | 'desc' // default: desc
  orderby?:
    | 'author'
    | 'date'
    | 'id'
    | 'include'
    | 'modified'
    | 'parent'
    | 'relevance'
    | 'slug'
    | 'include_slugs'
    | 'title' // default: date
  slug?: WpPostObjectFilter // TODO: check this
  status?: string // default: 'publish'
  taxRelation?: 'AND' | 'OR'
  categories?: WpPostObjectFilter // TODO: check this
  categoriesExclude?: WpPostObjectFilter // TODO: check this
  tags?: WpPostObjectFilter // TODO: check this
  tagsExclude?: WpPostObjectFilter // TODO: check this
  sticky?: boolean // TODO: check this
}

export interface GetPageParams
  extends Omit<
    GetPostParams,
    | 'orderby'
    | 'taxRelation'
    | 'categories'
    | 'categoriesExclude'
    | 'tags'
    | 'tagsExclude'
    | 'sticky'
  > {
  menuOrder: any // TODO: check this
  orderby?:
    | 'author'
    | 'date'
    | 'id'
    | 'include'
    | 'modified'
    | 'parent'
    | 'relevance'
    | 'slug'
    | 'include_slugs'
    | 'title'
    | 'menu_order' // default: date
  parent?: number[] // Limit result set to items with particular parent IDs.
  parentExclude?: number[] // TODO: number[] or number? > 'Limit result set to all items except those of a particular parent ID.'
}

export interface GetCommentParams {
  context?: 'view' | 'embed' | 'edit'
  page?: number
  perPage?: number
  search?: string
  after?: string // ISO8601
  author?: string | number
  authorExclude?: string | number
  author_email?: string
  before?: string // ISO8601
  exclude?: WpPostObjectFilter // TODO: check this
  include?: WpPostObjectFilter // TODO: check this
  offset?: number
  order?: 'asc' | 'desc' // default: desc
  orderby?: 'date' | 'date_gmt' | 'id' | 'include' | 'post' | 'parent' | 'type' // default: date
  parent?: number | number[] // Limit result set to items with particular parent IDs.
  parentExclude?: number | number[] // TODO: number[] or number? > 'Limit result set to all items except those of a particular parent ID.'
  post?: number | number[]
  status?: string // default: 'approve'
  type?: string // default: 'comment'
  password?: string
}

export default {
  getPosts({ ...args }: GetPostParams): AxiosPromise<any> {
    return request({
      url: '/wp/v2/posts',
      method: 'GET',
      params: snakecaseKeys({
        ...args,
      }),
    })
  },

  getPages({ ...args }: GetPageParams): AxiosPromise<any> {
    return request({
      url: '/wp/v2/pages',
      method: 'GET',
      params: snakecaseKeys({
        ...args,
      }),
    })
  },

  getComments({ ...args }: GetCommentParams): AxiosPromise<Comment[]> {
    return request({
      url: '/wp/v2/comments',
      method: 'GET',
      params: snakecaseKeys({
        ...args,
      }),
    })
  },
}
