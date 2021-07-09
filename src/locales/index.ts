import { createIntl } from '@formatjs/intl'
import { IntlConfig, createIntl as createIntlPlugin } from 'vue-intl'
import en from './en.json'

const config: IntlConfig = {
  locale: 'en',
  defaultLocale: 'en',
  messages: en,
}

const intl = createIntl(config)

const intlPlugin = createIntlPlugin(config)

export { intl as default, intlPlugin, config }
