import linkHandler from './linkHandler'
import { cloneDeep } from 'lodash'

describe('Test url handler', () => {
  const newUrl: any = new URL('https://wp.moezx.cc/')
  const newlocation: Location = newUrl as Location
  newlocation.assign = jest.fn()
  newlocation.replace = jest.fn()
  newlocation.reload = jest.fn()

  test('check an internal url with isExternal', () => {
    const url = 'https://wp.moezx.cc/some/test'
    const parse = new URL(url)
    const mockLocation = cloneDeep(newlocation)
    mockLocation.href = parse.href
    mockLocation.pathname = parse.pathname
    delete (window as any).location
    window.location = newlocation
    expect(linkHandler.isExternal(url)).toBe(false)
  })

  test('check an internal url with isInternal', () => {
    const url = 'https://wp.moezx.cc/some/test'
    const parse = new URL(url)
    const mockLocation = cloneDeep(newlocation)
    mockLocation.href = parse.href
    mockLocation.pathname = parse.pathname
    delete (window as any).location
    window.location = newlocation
    expect(linkHandler.isInternal(url)).toBe(true)
  })

  test('check an external url with isExternal', () => {
    const testUrl = 'https://next.router.vuejs.org/guide/#javascript'
    const url = 'https://wp.moezx.cc/some/test'
    const parse = new URL(url)
    const mockLocation = cloneDeep(newlocation)
    mockLocation.href = parse.href
    mockLocation.pathname = parse.pathname
    delete (window as any).location
    window.location = newlocation
    expect(linkHandler.isExternal(testUrl)).toBe(true)
  })

  test('check an external url with isInternal', () => {
    const testUrl = 'https://next.router.vuejs.org/guide/#javascript'
    const url = 'https://wp.moezx.cc/some/test'
    const parse = new URL(url)
    const mockLocation = cloneDeep(newlocation)
    mockLocation.href = parse.href
    mockLocation.pathname = parse.pathname
    delete (window as any).location
    window.location = newlocation
    expect(linkHandler.isInternal(testUrl)).toBe(false)
  })

  test('get internal router link', () => {
    const url = 'https://wp.moezx.cc/2021/06/30/python/?ref=233&errr=werwe#wowow?fsdf=ff'
    const parse = new URL(url)
    const mockLocation = cloneDeep(newlocation)
    mockLocation.href = parse.href
    mockLocation.pathname = parse.pathname
    delete (window as any).location
    window.location = newlocation
    expect(linkHandler.internalLinkRouterPath(url)).toBe(
      '/2021/06/30/python/?ref=233&errr=werwe#wowow?fsdf=ff'
    )
  })

  // test('parse relative path', () => {
  //   const testUrl = '../wowo'
  //   const url = 'https://wp.moezx.cc/some/test/index.php'
  //   const parse = new URL(url)
  //   const mockLocation = cloneDeep(newlocation)
  //   mockLocation.href = parse.href
  //   mockLocation.pathname = parse.pathname
  //   delete (window as any).location
  //   window.location = newlocation
  //   expect(linkHandler.urlParser(testUrl).href).toBe('https://wp.moezx.cc/some/wowo')
  // })
})
