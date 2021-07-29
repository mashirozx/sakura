import type { Router } from 'vue-router'
import logger from './logger'

export default class linkHandler {
  public static siteOrigin() {
    return window.location.origin
  }

  public static urlParser(url: string) {
    return new URL(url, window.location.href)
  }

  public static isInternal(url: string) {
    return this.urlParser(url).origin === this.siteOrigin()
  }

  public static isExternal(url: string) {
    return !this.isInternal(url)
  }

  public static internalLinkRouterPath(url: string) {
    if (this.isInternal(url)) {
      const parsed = this.urlParser(url)
      const { pathname, search, hash } = this.urlParser(parsed.href)
      return pathname + search + hash
    } else {
      throw new Error('Not internal link')
    }
  }

  public static handleClickLink({
    url,
    router,
    target,
  }: {
    url: string
    router: Router
    target?: '_blank' | '_self' | '_parent' | '_top'
  }) {
    logger('log', 'linkHandler: ', url)
    if (this.isInternal(url)) {
      // TODO: why not import? cause vue codes cannot pass the jest test...
      // router = router ?? ((window as any).router as Router)
      router.push(this.internalLinkRouterPath(url))
      // window.setTimeout(() => (window.location.hash = parsed.hash))
    } else {
      console.log('open: ', this.urlParser(url).href)
    }
  }
}
