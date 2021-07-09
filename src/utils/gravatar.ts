import { MD5 } from 'crypto-js'
import { LiteralUnion } from 'type-fest'

export interface Options {
  /**
	[Size](https://en.gravatar.com/site/implement/images/#size) of the image. Values: `1..2048`.
	@default 80
	*/
  readonly size?: number

  /**
	[Image](https://en.gravatar.com/site/implement/images/#default-image) to return if the identifier didn't match any Gravatar profile. Either a ustom URL or [`404`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=404), [`mm`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=mm), [`identicon`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=identicon), [`monsterid`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=monsterid), [`wavatar`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=wavatar), [`retro`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=retro), [`blank`](https://gravatar.com/avatar/5cc22f8c06631cccead907acbb627b69?default=blank).
	@default 'https://gravatar.com/avatar/00000000000000000000000000000000'
	*/
  readonly default?: LiteralUnion<
    '404' | 'mm' | 'identicon' | 'monsterid' | 'wavatar' | 'retro' | 'blank',
    string
  >

  /**
	Allowed [rating](https://en.gravatar.com/site/implement/images/#rating) of the image.
	@default 'g'
	*/
  readonly rating?: 'g' | 'pg' | 'r' | 'x'
}

/**
Get the URL to a Gravatar image from an identifier, such as an email.
@param identifier - Identifier for which to get the Gravatar image. This will typically be an email matching a Gravatar profile, but can technically be any string. The Gravatar service only sees a hash of the identifier, so you could actually use this to get pseudo-random avatars for any entity, e.g. based on its ID. Note that if the identifier contains an `@`, it is assumed to be an email, and will therefore be lower-cased and trimmed before hashing, as per the Gravatar instructions - otherwise it will be hashed as-is.
@example
```
import gravatarUrl from 'gravatar-url';
gravatarUrl('sindresorhus@gmail.com', {size: 200});
//=> 'https://gravatar.com/avatar/d36a92237c75c5337c17b60d90686bf9?size=200'
```
*/
export default function gravatarUrl(identifier: string, options?: Options): string {
  if (!identifier) {
    throw new Error('Please specify an identifier, such as an email address')
  }

  if (identifier.includes('@')) {
    identifier = identifier.toLowerCase().trim()
  }

  const baseUrl = new URL('https://gravatar.com/avatar/')
  baseUrl.pathname += MD5(identifier)
  baseUrl.search = new URLSearchParams(options as URLSearchParams).toString()

  return baseUrl.toString()
}

export const WP_DEFAULT_USER_EMAIL = 'wapuu@wordpress.example'
