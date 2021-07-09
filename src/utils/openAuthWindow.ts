import { v4 as uuidv4 } from 'uuid'
const openAuthWindow = async (applicationPasswordsEndpoint: string) => {
  const url =
    applicationPasswordsEndpoint +
    '?app_name=SakuraWeb&app_id=' +
    uuidv4() +
    '&success_url=https://wp.moezx.cc/sakura/auth/&reject_url=https://wp.moezx.cc/sakura/auth/'
  const target = 'ApplicationPasswordAuth'
  const features = [
    'toolbar=no',
    'menubar=no',
    'location=no',
    'width=600',
    'height=600',
    'top=' + (window.screen.height - 600) / 2,
    'left=' + (window.screen.width - 600) / 2,
  ]

  window.open(url, target, features.join(','))
}

export default openAuthWindow
