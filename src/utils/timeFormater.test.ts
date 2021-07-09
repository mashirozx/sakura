import timeFormater from './timeFormater'

describe('Test time formater', () => {
  const now = 1624851987839
  test('get readable time from now using ISO 8601 as param', () => {
    const time = '2021-06-25T07:22:19'
    expect(new timeFormater(time, now).getReadableTimeFromNow()).toBe('in 3 days')
  })
})
