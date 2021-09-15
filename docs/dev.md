# Dev configurations

.env.development

```env
SSH_KEY_PATH='~/.ssh/id_rsa'
SSH_REMOTE_HOST='root@8.8.8.8'
SSH_REMOTE_WORK_DIR='/var/www/html/wp-contents/themes/sakura-next'
```

add this rewrite rule to Nginx:

```nginx
location /src/assets {
  rewrite ^/(.*)$ http://localhost:9000/$1 redirect;
}
```

> Error: spawn C:\..\node_modules\esbuild\esbuild.exe ENOENT

```bash
node ./node_modules/esbuild/install.js
```

ref: <https://github.com/vitejs/vite/issues/1361>
