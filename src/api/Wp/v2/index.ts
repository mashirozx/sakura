import request, { AxiosPromise } from '@/utils/http'
import snakecaseKeys from 'snakecase-keys'

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
  exclude?: number | number[]
  include?: number | number[]
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
  slug?: string
  status?: string // default: 'publish'
  taxRelation?: 'AND' | 'OR'
  categories?: number | number[]
  categoriesExclude?: number | number[]
  categoriesSlug?: string
  tags?: number | number[]
  tagsExclude?: number | number[]
  tagsSlug?: string
  sticky?: boolean // TODO: check this
}

export interface GetPageParams
  extends Omit<
    GetPostParams,
    | 'orderby'
    | 'taxRelation'
    | 'categories'
    | 'categoriesExclude'
    | 'categoriesSlug'
    | 'tags'
    | 'tagsExclude'
    | 'tagsSlug'
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
  exclude?: number | number[] // TODO: check this
  include?: number | number[] // TODO: check this
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

  // postApplicationPasswords({userId,}){
  //   return request({
  //     url: '/wp/v2/users/<user_id>)/application-passwords',
  //     method: 'POST',
  //     params: snakecaseKeys({
  //       ...args,
  //     }),
  //   })
  //   //
  // }
}
