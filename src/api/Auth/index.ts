import request from '@/utils/http'

export default {
  postJwtAuthToken({ username, password }: { username: string; password: string }): Promise<any> {
    return request({
      url: '/jwt-auth/v1/token',
      method: 'POST',
      data: { username, password },
    })
  },
}
