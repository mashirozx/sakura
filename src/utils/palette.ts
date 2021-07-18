import { cloneDeep } from 'lodash'
import chroma from 'chroma-js'

export interface Scheme {
  // base
  primary: string
  secondary: string
  background: string
  surface: string
  error: string
  // text color of a * background
  'on-primary': string
  'on-secondary': string
  'on-background': string
  'on-surface': string
  'on-error': string
  // modifier
  'primary-lighter-25'?: string
  'primary-darker-10'?: string
}

/**
 * @param scheme
 * @returns CSS variables object using directly in :style
 */
export default function (scheme: Scheme) {
  const modifier = {
    'primary-lighter-25': chroma(scheme.primary).brighten(2.5).hex(),
    'primary-darker-10': chroma(scheme.primary).darken(1).hex(),
  }
  const _scheme = cloneDeep(Object.assign(scheme, modifier))

  const colors: { [key: string]: string } = {}
  Object.keys(scheme).forEach(
    (key) => (colors[`--mdc-theme-${key}`] = _scheme[key as keyof typeof scheme])
  )

  return colors
}
