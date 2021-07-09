import { IntlShape } from '@formatjs/intl'
import { provideIntl, useIntl } from 'vue-intl'

const useIntlProvider = (intl: IntlShape<string>) => {
  provideIntl(intl)
}

export { useIntl, useIntlProvider }
