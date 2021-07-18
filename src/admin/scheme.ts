import palette, { Scheme } from '@/utils/palette'

const config: { [key: string]: Scheme } = {
  Default: {
    primary: '#2271b1',
    secondary: '#72aee6',
    background: '#f0f0f1',
    surface: '#ffffff',
    error: '#d63638',
    'on-primary': '#ffffff',
    'on-secondary': '#ffffff',
    'on-background': '#1d2327',
    'on-surface': '#3c434a',
    'on-error': '#ffffff',
  },
}

const { name } = (window as any).AdminColors as { [key: string]: keyof typeof config }

const theConfig = config[name] ?? config['Default']

const scheme = palette(theConfig)

export default scheme
