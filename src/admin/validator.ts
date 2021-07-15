import logger from '@/utils/logger'
export default function validator<T>(value: T, type: string): { pass: boolean; msg?: string } {
  switch (type) {
    case 'string':
    case 'longString':
      return { pass: typeof value === 'string' }
    case 'choose':
      return { pass: typeof value === 'number' }
    case 'switcher':
      return { pass: typeof value === 'boolean' }
    case 'selection':
      return { pass: value instanceof Array }
    case 'mediaPicker':
      return { pass: value instanceof Array }
    default:
      const msg = `No such type ${type}`
      logger('error', msg)
      return { pass: false, msg }
  }
}
