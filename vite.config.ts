import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
// import legacy from '@vitejs/plugin-legacy'
import path from 'path'
import svgicon from 'vite-plugin-svgicon'

// https://vitejs.dev/config/
export default defineConfig({
  base: 'http://localhost:9000/',
  resolve: {
    alias: [
      { find: '@', replacement: '/src/' },
      { find: 'vue', replacement: 'vue/dist/vue.esm-bundler.js' },
    ],
  },
  plugins: [
    vue(),
    svgicon({
      include: ['**/icons/**/*.svg'],
    }),
    // legacy({
    //   targets: ['defaults', 'not IE 11'],
    // }),
  ],
  server: {
    host: '0.0.0.0',
    cors: true,
    strictPort: true,
    port: 9000,
    hmr: {
      // TODO: .env
      protocol: 'ws',
      // host: '192.168.28.26',
      host: 'localhost',
      port: 9000,
    },
  },
  build: {
    target: 'modules',
    manifest: true,
    sourcemap: true,
    outDir: 'app/assets/dist',
    chunkSizeWarningLimit: 2048,
    rollupOptions: {
      // external: ['vue'],
      input: {
        main: path.resolve(__dirname, 'src/main.ts'),
        admin: path.resolve(__dirname, 'src/admin/main.ts'),
      },
      output: {
        // TODO: use ES5 bundle instead
        // globals: {
        //   vue: 'Vue',
        // },
        // manualChunks: undefined,
      },
    },
  },
  css: {
    preprocessorOptions: {
      scss: {
        sassOptions: { quietDeps: true },
      },
    },
  },
})
