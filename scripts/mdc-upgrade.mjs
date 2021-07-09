'use strict'
import { exec } from 'child_process'
import { readFileSync } from 'fs'

const json = JSON.parse(readFileSync('package.json'))

const dependencies = json.dependencies

let deps = ''

Object.keys(dependencies).forEach((key) => {
  if (/@material\//.test(key)) deps += ` ${key}@canary `
})

const command = `yarn upgrade ${deps}`

console.log(command)

exec(command, (error, stdout, stderr) => {
  if (error) console.error(error)
  if (stderr) console.warn(stderr)
  if (stdout) console.log(stdout)
})
