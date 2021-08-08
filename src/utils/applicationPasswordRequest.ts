import { v4 as uuidv4 } from 'uuid'

export default function (applicationPasswordsEndpoint: string, siteUrl: string) {
  const url = `${applicationPasswordsEndpoint}?app_name=SakuraWeb&app_id=${uuidv4()}&success_url=siteUrl/sakura/auth/&reject_url=${siteUrl}/sakura/auth/`
  return url
}
