import type { SakuraOptions as SakuraOptionsAbstract } from '@/admin/optionsType'

export interface SakuraOptions extends SakuraOptionsAbstract {
  [namespace: string]: any
}

const config = (window as any).InitState.config as SakuraOptions

export default config
