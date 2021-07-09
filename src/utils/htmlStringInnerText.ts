const htmlStringInnerText = (str: string) => {
  str = str.replace(/<img[^>]*>/g, '')
  const dom = document.createElement('div')
  dom.innerHTML = str
  // http://perfectionkills.com/the-poor-misunderstood-innerText/
  return dom.textContent?.toString() || ''
}

export default htmlStringInnerText
