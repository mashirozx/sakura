import request from '@/utils/http'
import Auth from './Auth'
import Sakura from './Sakura'
import Wp from './Wp'
export default {
  Auth,
  Sakura,
  Wp,
  getWpJson(): Promise<any> {
    return request({
      url: '/',
      method: 'GET',
    })
  },
}
