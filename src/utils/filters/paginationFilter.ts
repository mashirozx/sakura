import { AxiosResponse } from 'axios'
import camelcaseKeys from 'camelcase-keys'

export function getPagination(res: AxiosResponse<any>): Pagination {
  const params = camelcaseKeys(res.config.params)
  let { page, perPage }: { [key: string]: number } = params

  page = page ?? 1
  perPage = perPage ?? 10 // TODO: get site setting?
  const headers = res.headers
  const totalCount = Number(headers['x-wp-total'])
  const totalPage = Number(headers['x-wp-totalpages'])

  return {
    page,
    perPage,
    totalPage,
    totalCount,
  }
}
