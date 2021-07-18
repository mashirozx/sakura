import { Ref } from 'vue'
import { useState } from '@/hooks'
import API from '@/api'
import camelcaseKeys from 'camelcase-keys'
import intl from '@/locales'
import options, { Options } from './options'
import { cloneDeep } from 'lodash'

export interface OptionStore {
  [namespace: string]: any
}

export default function auth(): object {
  const wpLocalizeScript = (window as any).SakuraOptions?.data as OptionStore
  const initConfig = cloneDeep(wpLocalizeScript ?? {})
  const [config, setConfig] = useState(initConfig, false)

  const updateOption = (configState: Ref<OptionStore>, key: string, value: any) => {
    const config = cloneDeep(configState.value)
    config[key] = value
    setConfig(config)
  }

  return { config, updateOption }
}
