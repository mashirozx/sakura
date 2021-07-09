import htmlStringInnerText from './htmlStringInnerText'

describe('Test getting HTML string innerText', () => {
  test('get innerText without line breaks', () => {
    const html = '<p>Installation Vue.js is built by design to be incrementa [&hellip;]</p>'
    const result = htmlStringInnerText(html)
    const expected = 'Installation Vue.js is built by design to be incrementa […]'
    expect(result).toBe(expected)
  })

  test('get innerText with trilling line breaks', () => {
    const html = '<p>Installation Vue.js is built by design to be incrementa [&hellip;]</p>\n'
    const result = htmlStringInnerText(html)
    const expected = 'Installation Vue.js is built by design to be incrementa […]\n'
    expect(result).toBe(expected)
  })

  test('get innerText with line breaks', () => {
    const html =
      '<h1>Installation</h1>\n<p>Vue.js is built by design to be incrementally adoptable. This means that it can be integrated into a project multiple ways depending on the requirements.</p>'
    const result = htmlStringInnerText(html)
    const expected =
      'Installation\nVue.js is built by design to be incrementally adoptable. This means that it can be integrated into a project multiple ways depending on the requirements.'
    expect(result).toBe(expected)
  })
})
