import { MD5 } from 'crypto-js'

export default function () {
  return MD5(Math.random().toString()).toString().slice(0, 8)
}
