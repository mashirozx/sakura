/**
 * WordPress rewrite rules parser
 * For: https://github.com/WordPress/wordpress-develop/blob/master/src/wp-includes/class-wp-rewrite.php
 *
 * @author: Mashiro (https://github.com/mashirozx)
 * @since 0.0.1
 */

class RewriteRulesParser {
  // original permalink_structure string in init state
  public permalinkStructure = window.InitState.routing['permalink_structure'] as string
  public tagBase = window.InitState.routing['tag_base'] as string
  public categoryBase = window.InitState.routing['category_base'] as string
  public showOnFront = window.InitState.routing['show_on_front'] as string
  public pageOnFront = window.InitState.routing['page_on_front']
  public pageForPosts = window.InitState.routing['page_for_posts']
  // wp endpoint base
  public index: string = window.InitState.index
  // key-value reversed WP_Rewrite::$rewrite_rules()
  public rewriteRules: { [key: string]: string } = {}
  // public permalinkStructureMatch: string[] = []
  // post's dynamic path in vue-router
  public singleRouter?: string
  // https://example.com/taxonomyBase%first_match%...
  public taxonomyBase?: string

  public constructor() {
    this.matchPermalinkStructure()
  }

  public permalinkStructureRegex = /(?:\/([^\/]*))/g

  public rewriteCodeMap: { [key: string]: string } = {
    year: '%year%',
    monthnum: '%monthnum%',
    day: '%day%',
    hour: '%hour%',
    minute: '%minute%',
    second: '%second%',
    postname: '%postname%',
    postId: '%post_id%',
    author: '%author%',
    pagename: '%pagename%',
    search: '%search%',
    category: '%category%',
  }

  // see: WP_Rewrite::$rewritereplace
  public routerCodeMap: { [key: string]: string } = {
    year: ':year(\\d{4})',
    monthnum: ':monthnum(\\d{1,2})',
    day: ':day(\\d{1,2})',
    hour: ':hour(\\d{1,2})',
    minute: ':minute(\\d{1,2})',
    second: ':second(\\d{1,2})',
    postname: ':postname([^/]+)',
    postId: ':postId(\\d+)',
    author: ':author([^/]+)',
    pagename: ':pagename([^/]+)',
    search: ':search([^/]+?)',
    category: ':category(.+)',
  }

  public queryreplace = [
    'year=',
    'monthnum=',
    'day=',
    'hour=',
    'minute=',
    'second=',
    'name=',
    'p=',
    'author_name=',
    'pagename=',
    's=',
  ]

  // Read and parse init state
  public read() {
    let rewriteRules: { [key: string]: string } = {}
    if (window.InitState) {
      rewriteRules = InitState['rewrite_rules']
    }
    // this.permalinkStructureMatch = this.permalinkStructure?.match(this.permalinkStructureRegex)

    const rewriteRulesReverse: { [key: string]: string } = {}
    Object.keys(rewriteRules).forEach((key) => {
      const value = rewriteRules[key] as string
      rewriteRulesReverse[value] = key
    })
    this.rewriteRules = rewriteRulesReverse
  }

  public matchPermalinkStructure() {
    let singleRouter = this.permalinkStructure || ''

    Object.keys(this.rewriteCodeMap).forEach((key) => {
      const code = this.rewriteCodeMap[key]
      const routerCode = this.routerCodeMap[key]

      singleRouter = singleRouter.replace(code, routerCode)
    })

    this.taxonomyBase = this.permalinkStructure.slice(0, this.permalinkStructure.indexOf('%'))
    this.singleRouter = singleRouter.slice(this.permalinkStructure.indexOf('%'))
  }
}

export default RewriteRulesParser
