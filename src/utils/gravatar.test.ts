import gravatar from './gravatar'

describe('Test getting Gravatar url', () => {
  test('get url by email', () => {
    expect(gravatar('wapuu@wordpress.example')).toBe(
      'https://gravatar.com/avatar/d7a973c7dab26985da5f961be7b74480'
    )
  })
})
