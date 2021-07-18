import options from './options'
import { writeFileSync } from 'fs'

const exportOptions: { [key: string]: any } = {}

Object.keys(options).forEach((tab) => {
  options[tab].options.forEach((option) => {
    if (option.depends) delete option.depends // remove function
    exportOptions[option.namespace] = option
  })
})

console.dir(exportOptions)

writeFileSync('./app/configs/options.json', JSON.stringify(exportOptions, null, 2), { flag: 'w+' })
