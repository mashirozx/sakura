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
  const [config, setConfig]: [Ref<OptionStore>, (arg: OptionStore) => void] = useState({})

  const updateOption = (configState: Ref<OptionStore>, key: string, value: any) => {
    const config = cloneDeep(configState.value)
    config[key] = value
    setConfig(config)
  }

  // const saveOption
  // const resetOption
  // const resetAllOption

  // const mapOption = (configState: Ref<OptionStore>) => {
  //   const config = cloneDeep(configState.value)
  //   const data: OptionStore = {}
  //   Object.keys(options).forEach((tagKey) => {
  //     const tag = options[tagKey]
  //     Object.keys(tag.options).forEach((namespace) => {
  //       data[tagKey][namespace].payload = config[namespace]
  //     })
  //   })
  //   return data
  // }

  return { config, setConfig }
}
