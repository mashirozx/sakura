import axios, { AxiosPromise } from 'axios'
import camelcaseKeys from 'camelcase-keys'

const instance = axios.create({
  baseURL: camelcaseKeys(window.InitState)?.apiBase,
  timeout: 10000,
})

export { instance as default, AxiosPromise }
