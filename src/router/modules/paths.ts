import { RouteRecordRaw } from 'vue-router'
import RewriteRulesParser from '@/router/utils/parser'
import pagination from '@/router/modules/pagination'
import commentPagination from '@/router/modules/commentPagination'
import Home from '@/views/Home.vue'
import Single from '@/views/Single.vue'
import Page from '@/views/Page.vue'
import Tag from '@/views/Tag.vue'
import Category from '@/views/Category.vue'
import Author from '@/views/Author.vue'
import Date from '@/views/Date.vue'
import Search from '@/views/Search.vue'
import Error from '@/views/Error.vue'
import Loading from '@/views/Loading.vue'
import Auth from '@/views/Auth.vue'

const parser = new RewriteRulesParser()
const { taxonomyBase, singleRouter, tagBase, categoryBase } = parser

const homeRouterRecoed: RouteRecordRaw = {
  path: '/',
  component: Home,
  name: 'Home',
}

const AuthRouterRecored: RouteRecordRaw = {
  path: '/sakura/auth',
  component: Auth,
  name: 'Auth',
}

const categoryArchiveRecord: RouteRecordRaw = {
  path: categoryBase
    ? `/${categoryBase}/:cat1/:cat2?/:cat3?`
    : `${taxonomyBase || '/'}category/:cat1/:cat2?/:cat3?`,
  component: Category,
  name: 'CategoryArchive',
  children: [pagination(Category, 'Category')],
}

const tagArchiveRecord: RouteRecordRaw = {
  path: tagBase ? `/${tagBase}/tag/:tag` : `${taxonomyBase || '/'}tag/:tag`,
  component: Tag,
  name: 'TagArchive',
  children: [pagination(Tag, 'TagArchive')],
}

const authorArchiveRecord: RouteRecordRaw = {
  path: `${taxonomyBase || '/'}author/:author`,
  component: Author,
  name: 'AuthorArchive',
  children: [pagination(Author, 'AuthorArchive')],
}

const searchPageRecord: RouteRecordRaw = {
  path: '/search/:keyword',
  component: Search,
  name: 'Search',
  children: [pagination(Search, 'AuthorArchive')],
}

const dateArchiveRecord: RouteRecordRaw = {
  path: `${taxonomyBase || '/'}:year(\\d{4})`,
  component: Date,
  name: 'DateYearArchive',
  children: [
    {
      path: ':monthnum(\\d{1,2})',
      component: Date,
      name: 'DateMonthArchive',
      children: [
        {
          path: ':day(\\d{1,2})',
          component: Date,
          name: 'DataDayArchive',
          children: [pagination(Date, 'DataDayArchive')],
        },
        pagination(Date, 'DateMonthArchive'),
      ],
    },
    pagination(Date, 'DateDayArchive'),
  ],
}

// The single post path
const singleRecord: RouteRecordRaw = {
  path: (taxonomyBase || '/') + singleRouter,
  component: Single,
  name: 'Single',
  children: [pagination(Single, 'Single'), commentPagination(Single, 'Single')],
}

// https://example.com/slugname will be redirect to the single post page if it is a single post
const pageRecord: RouteRecordRaw = {
  path: '/:slug',
  component: Page,
  name: 'Page',
  children: [pagination(Single, 'Page'), commentPagination(Single, 'Page')],
}

const ErrorPageRecord: RouteRecordRaw = {
  path: '/*',
  component: Error,
  name: 'Error',
}

export {
  homeRouterRecoed,
  categoryArchiveRecord,
  authorArchiveRecord,
  tagArchiveRecord,
  searchPageRecord,
  dateArchiveRecord,
  singleRecord,
  pageRecord,
  ErrorPageRecord,
  AuthRouterRecored,
}
