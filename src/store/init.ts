import { useState } from '@/hooks'
import API from '@/api'
import camelcaseKeys from 'camelcase-keys'
import intl from '@/locales'

const messages = {
  applicationPasswordsEndpointNotAvailable: intl.formatMessage({
    id: 'messages.wordpress.applicationPasswordsEndpointNotAvailable',
    defaultMessage:
      'ApplicationPasswords is not avaliabe in your WordPress installation, please upgrade WordPress to v5.6.0 above, or enable the ApplicationPasswords feature in v5.6.0 above installation.',
  }),
}

export default function auth(): object {
  // @ts-ignore
  const [initState, setInitState] = useState(window.InitState ?? {})
  const [wpJson, setWpJson] = useState({})
  const [applicationPasswordsEndpoint, setApplicationPasswordsEndpoint] = useState('')

  const fetchInitState = async ({ username, password }: { [key: string]: string }) => {
    await new Promise((resolve, reject) => {
      API.Sakura.v1
        .getInitState()
        .then((res) => {
          const data = camelcaseKeys(res.data)
          setInitState(data)
          resolve(null)
        })
        .catch((error) => {
          console.error(error)
          reject(null)
        })
    })
  }

  const fetchWpJson = async () => {
    await new Promise((resolve, reject) => {
      API.getWpJson()
        .then((res) => {
          const data = camelcaseKeys(res.data)
          setWpJson(data)
          if (camelcaseKeys(data?.authentication)?.applicationPasswords?.endpoints?.authorization) {
            setApplicationPasswordsEndpoint(
              camelcaseKeys(data.authentication).applicationPasswords.endpoints.authorization
            )
          } else {
            console.warn(messages.applicationPasswordsEndpointNotAvailable)
            setApplicationPasswordsEndpoint('')
          }

          resolve(null)
        })
        .catch((error) => {
          console.log(error)
          reject(error)
        })
    })
  }

  return {
    initState,
    setInitState,
    fetchInitState,
    wpJson,
    setWpJson,
    fetchWpJson,
    applicationPasswordsEndpoint,
    setApplicationPasswordsEndpoint,
  }
}
