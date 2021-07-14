import request from '@/utils/http'

export default {
  postConfigJson(config: any): Promise<any> {
    return request({
      url: '/sakura/v1/config',
      method: 'POST',
      data: config,
    })
  },
}
