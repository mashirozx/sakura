import intl from '@/locales'
import htmlStringInnerText from '@/utils/htmlStringInnerText'
import camelcaseKeys from 'camelcase-keys'
import publishTime from './publishTime'

export default function (post: Post, type: 'single' | 'page' | 'thumbList') {
  const id = post.id
  const title = post.title.rendered

  const publistTime = publishTime(post.date)
  const publistTimeBrief = publishTime(post.date, true)

  const readCount = intl.formatMessage(
    {
      id: 'posts.readCount',
      defaultMessage:
        '{readCount, plural, =0 {No one ever read} =1 {One read} other {{readCount, number, ::compact-short} Reads}}',
    },
    { readCount: post.viewCount }
  )

  const commentCount = intl.formatMessage(
    {
      id: 'posts.commentCount',
      defaultMessage:
        '{commentCount, plural, =0 {No comment} =1 {One comment} other {{commentCount, number, ::compact-short} Comments}}',
    },
    { commentCount: post.commentCount }
  )

  const wordCount = intl.formatMessage(
    {
      id: 'posts.wordCount',
      defaultMessage:
        '{wordCount, plural, =0 {No content} =1 {One word} other {{wordCount, number, ::compact-short} Words}}',
    },
    // { wordCount: htmlStringInnerText(post.content.rendered).length }
    { wordCount: post.wordsCount }
  )

  const excerpt = post.excerpt.plain || htmlStringInnerText(post.excerpt.rendered)

  const featureImage = {
    thumbnail: post.featuredMediaMeta.sizes
      ? camelcaseKeys(post.featuredMediaMeta.sizes).large
        ? camelcaseKeys(post.featuredMediaMeta.sizes).large.url
        : post.featuredMediaMeta.url
      : null,
    original: post.featuredMediaMeta.sizes ? post.featuredMediaMeta.url : null,
  }

  const author: any = camelcaseKeys(post.authorMeta)
  author['nickname'] = author.nickname || author.userNicename || author.displayName

  const content = post.content ?? ''
  const link = post.link

  const tags = post.tagsMeta ? camelcaseKeys(post.tagsMeta) : []

  const data = {
    id,
    publistTime,
    publistTimeBrief,
    title,
    readCount,
    commentCount,
    wordCount,
    excerpt,
    featureImage,
    author,
    content,
    link,
    tags,
  }

  return data
}
