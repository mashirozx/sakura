#!/usr/local/bin/node
import { exec } from 'child_process'
import colors from 'colors'
import dotenv from 'dotenv'

const start = Date.now()

dotenv.config({ path: '.env.development' })

const argv = process.argv.slice(2)

const app = `rsync -a -P -e "ssh -i ${process.env.SSH_KEY_PATH}" ./app/ ${process.env.SSH_REMOTE_HOST}:${process.env.SSH_REMOTE_WORK_DIR}/app/ --exclude 'vendor' --exclude 'cache' --delete -ic`

let composer = `rsync -a -P -e "ssh -i ${process.env.SSH_KEY_PATH}" ./composer.* ${process.env.SSH_REMOTE_HOST}:${process.env.SSH_REMOTE_WORK_DIR}/ -ic`

const rmLF = (str) => {
  if (str.lastIndexOf('\n') > 0) {
    return str.substring(0, str.lastIndexOf('\n'))
  } else {
    return str
  }
}

if (argv.indexOf('--composer-install') >= 0)
  composer += ` && ssh -i ${process.env.SSH_KEY_PATH} ${process.env.SSH_REMOTE_HOST} 'cd ${process.env.SSH_REMOTE_WORK_DIR} && COMPOSER_ALLOW_SUPERUSER=1 composer install'`

let command
// TODO: separate these!
if (argv.indexOf('--geoip2') >= 0) {
  command = `ssh -i ${process.env.SSH_KEY_PATH} ${process.env.SSH_REMOTE_HOST} 'cd ${process.env.SSH_REMOTE_WORK_DIR} && mkdir -p app/cache && curl -o app/cache/GeoLite2-City.mmdb https://raw.githubusercontent.com/P3TERX/GeoLite.mmdb/download/GeoLite2-City.mmdb'`
} else if (argv.indexOf('--qqwry') >= 0) {
  command = `ssh -i ${process.env.SSH_KEY_PATH} ${process.env.SSH_REMOTE_HOST} 'cd ${process.env.SSH_REMOTE_WORK_DIR} && mkdir -p app/cache && curl -o app/cache/qqwry.dat https://raw.githubusercontent.com/out0fmemory/qqwry.dat/master/qqwry_lastest.dat'`
} else if (argv.indexOf('--composer') < 0 && argv.indexOf('--composer-install') < 0) {
  command = app
} else {
  command = composer
}

// console.log(command)

exec(command, (error, stdout, stderr) => {
  if (stdout) stdout = stdout.replace('sending incremental file list\n', '')
  if (error) {
    console.log(rmLF(error.message))
    console.log(
      colors.inverse(colors.magenta(' rsync error '), ` ready in ${Date.now() - start}ms `)
    )
    return
  }
  if (stderr) {
    console.log(rmLF(stderr))
    console.log(
      colors.inverse(colors.magenta(' rsync stderr '), ` ready in ${Date.now() - start}ms `)
    )
    return
  }
  if (stdout.trim())
    // --itemize-changes: https://www.samba.org/ftp/rsync/rsync.html
    console.log(colors.inverse(colors.green('YXcstogax File '), 'See: https://git.io/JnrAP '))
  console.log(stdout.trim() ? rmLF(stdout) : colors.green('Already up to date, nothing to sync.'))
  console.log(colors.inverse(colors.green(' rsync done '), ` ready in ${Date.now() - start} ms `))
})
