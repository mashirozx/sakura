import { Router } from 'vue-router'
import logger from '@/utils/logger'

export function applyGuards(router: Router) {
  // Global Before Guards
  // router.beforeEach((to, from) => {
  //   return to
  // })

  // Global Resolve Guards
  // router.beforeResolve(async to => {
  // })

  // Global After Hooks
  router.afterEach((to, from, failure) => {
    if (failure && to.fullPath !== from.fullPath) {
      logger('warn', 'from', from, '\nto', to, '\n', failure)
    }
  })
}
