import request from '@/utils/http'
import snakecaseKeys from 'snakecase-keys'

export interface CreateCommentParameters {
  author?: string // The ID of the user object, if author was a user.
  authorEmail?: string // Email address for the object author.
  authorIp?: string // IP address for the object author.
  authorName?: string // Display name for the object author.
  authorUrl?: string // URL for the object author.
  authorUserAgent?: string // User agent for the object author.
  content: string // * The content for the object.
  date?: string // The date the object was published, in the site's timezone.
  dateGmt?: string // The date the object was published, as GMT.
  parent: number // * The ID for the parent of the object.
  post: number // * The ID of the associated post object.
  status?: string // State of the object.
  meta?: { [key: string]: any } // Meta fields.
}
export interface UpdateCommentParameters {
  id: number // Unique identifier for the object.
  author: string // The ID of the user object, if author was a user.
  authorEmail: string // Email address for the object author.
  authorIp: string // IP address for the object author.
  authorName: string // Display name for the object author.
  authorUrl: string // URL for the object author.
  authorUserAgent: string // User agent for the object author.
  content: string // The content for the object.
  date: string // The date the object was published, in the site's timezone.
  dateGmt: string // The date the object was published, as GMT.
  parent: number // The ID for the parent of the object.
  post: number // The ID of the associated post object.
  status: string // State of the object.
  meta: { [key: string]: any } // Meta fields.
}

export default {
  getInitState(): Promise<any> {
    return request({
      url: '/sakura/v1/init-state',
      method: 'GET',
    })
  },

  getMenu({ location }: { location?: string }): Promise<any> {
    return request({
      url: '/sakura/v1/menu',
      method: 'GET',
      params: {
        location,
      },
    })
  },

  createComment<T extends CreateCommentParameters>(
    { authorEmail, authorName, authorUrl, content, parent, post }: T,
    auth?: { username: string; password: string }
  ): Promise<any> {
    const form = snakecaseKeys({ authorEmail, authorName, authorUrl, content, parent, post })

    const formData = new FormData()

    Object.keys(form).forEach((key) => {
      if (form[key] !== undefined) formData.append(key, form[key])
    })

    return request({
      url: '/sakura/v1/comments',
      method: 'POST',
      data: formData,
      auth,
    })
  },
}
