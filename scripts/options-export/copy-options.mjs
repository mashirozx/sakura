import { readFileSync, writeFileSync } from 'fs'

let file = readFileSync('./src/admin/options.ts', { flag: 'r' }).toString()
file = file.replace('@/locales', './locales')
writeFileSync('./scripts/options-export/options.ts', file, { flag: 'w+' })
