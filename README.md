# sakura-next

## Requirement

- PHP=7.4
- WordPress>=5.6.0
- Node.js>=14.17.1
- yarn>=1.22.10

## Commands

```sh
composer install # install php dependencies
yarn install # install node dependencies
yarn dev # run dev server
yarn build # build assets
yarn test # Jest test
yarn lint # eslint
yarn format # auto format
yarn i18n # export i18n variables
yarn rsync # sync backend app with server, see docs/dev.md
yarn composer # run `composer install` on remote server
yarn gen:icon # generate svg icon component
yarn remote-download:geoip2 # download GeoIP db on remote server
yarn local-download:geoip2 # download GeoIP db locally
```
