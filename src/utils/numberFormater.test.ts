import numberFormater from './numberFormater'

describe('Test number formater', () => {
  const bitNumber = 2333333333333333
  test('get big number readable string in Chinese (万亿、亿、万)', () => {
    const formater = new numberFormater('zh-CN')
    expect(formater.fullFormat(bitNumber)).toBe('2333万亿3333亿3333万3333')
  })

  test('get big number readable string in Japanese (兆、億、万)', () => {
    const formater = new numberFormater('ja-JP')
    expect(formater.fullFormat(bitNumber)).toBe('2333兆3333億3333万3333')
  })

  test('get big number readable string in English (T, B, M, K)', () => {
    const formater = new numberFormater('en-US')
    expect(formater.fullFormat(bitNumber)).toBe('2333T333B333M333K333')
  })

  const smallNumber = 23333
  test('get small number readable string in Chinese', () => {
    const formater = new numberFormater('zh-CN')
    expect(formater.format(smallNumber)).toBe('2.3万')
  })

  test('get small number readable string in English', () => {
    const formater = new numberFormater('en-US')
    expect(formater.format(smallNumber)).toBe('23K')
  })
})
