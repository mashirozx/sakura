import { usePersistedState } from '@/hooks'
import API from '@/api'
import camelcaseKeys from 'camelcase-keys'

export default function auth(): object {
  // JWT Auth token (deprecated)
  const [token, setToken] = usePersistedState('token', '')
  const [userDisplayName, setUserDisplayName] = usePersistedState('userDisplayName', '')
  const [userEmail, setUserEmail] = usePersistedState('userEmail', '')
  const [userNicename, setUserNicename] = usePersistedState('userNicename', '')
  // Application Password
  const [siteUrl, setSiteUrl] = usePersistedState('siteUrl', '')
  const [userLogin, setUserLogin] = usePersistedState('userLogin', '')
  const [password, setPassword] = usePersistedState('password', '')

  const jwtLogin = async ({ username, password }: { [key: string]: string }) => {
    await new Promise((resolve, reject) => {
      API.Auth.postJwtAuthToken({ username, password })
        .then((res) => {
          const { token, userDisplayName, userEmail, userNicename } = camelcaseKeys(res.data)
          setToken(token)
          setUserDisplayName(userDisplayName)
          setUserEmail(userEmail)
          setUserNicename(userNicename)
          resolve(null)
        })
        .catch((error) => {
          console.error(error)
          reject(null)
        })
    })
  }

  const setApplicationPassword = ({
    siteUrl,
    userLogin,
    password,
  }: {
    [key: string]: string
  }): void => {
    setSiteUrl(siteUrl)
    setUserLogin(userLogin)
    setPassword(password)
  }

  return {
    token,
    setToken,
    userDisplayName,
    setUserDisplayName,
    userEmail,
    setUserEmail,
    userNicename,
    setUserNicename,
    jwtLogin,
    siteUrl,
    setSiteUrl,
    userLogin,
    setUserLogin,
    password,
    setPassword,
    setApplicationPassword,
  }
}
