import request from '@/utils/http'

export default {
  postConfigJson(data: { [key: string]: any }): Promise<any> {
    return request({
      url: '/sakura/v1/config',
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      data: data,
    })
  },
}
