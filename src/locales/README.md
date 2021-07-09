# Translation

There are two ways to import `intl`

## 1. using composition API

```ts
// in setup()
import { useIntl } from '@/hooks'

const intl = useIntl()

const msg = intl.formatMessage({
  id: 'hello.content',
  defaultMessage: 'Hello world~~~',
})
console.log(msg)
```

## 2. import intl object directly

You can use this method anywhere, event a single `.ts` file:

```ts
import intl from '@/locales'
const msg = intl.formatMessage({
  id: 'hello.content',
  defaultMessage: 'Hello world~~~',
})
console.log(msg)
```

## example

```text
{name} took {numPhotos, plural, =0 {no photos} =1 {one photo} other {# photos}} on {takenDate, date, long}.
```
