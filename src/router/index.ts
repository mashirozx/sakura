import { createRouter, createWebHistory } from 'vue-router'
import {
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
} from './modules/paths'
import { applyGuards } from './guards'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    homeRouterRecoed,
    AuthRouterRecored,
    tagArchiveRecord,
    categoryArchiveRecord,
    authorArchiveRecord,
    searchPageRecord,
    dateArchiveRecord,
    singleRecord,
    pageRecord,
    ErrorPageRecord,
  ],
})

applyGuards(router)

export default router
