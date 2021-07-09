import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
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
      // path?: string,
      // timeout?: number,
      // overlay?: boolean
    },
  },
  build: {
    manifest: true,
    sourcemap: true,
    outDir: 'app/assets/dist',
    chunkSizeWarningLimit: 2048,
    // lib: {
    //   entry: path.resolve(__dirname, 'src/main.ts'),
    //   name: 'sakura-next',
    //   formats: ['umd'],
    // },
  },
  css: {
    preprocessorOptions: {
      scss: {
        sassOptions: { quietDeps: true },
      },
    },
  },
  // optimizeDeps: {
  //   exclude: ['@fortawesome/fontawesome-free'],
  // },
  // outputDir: path.resolve(__dirname, 'app/dist'),
  // publicPath: process.env.NODE_ENV === 'production' ? './' : 'http://localhost:9000/',
  // configureWebpack: (config) => {
  //   config.output.filename = process.env.NODE_ENV === 'production' ? 'sakura-app.[chunkhash].js' : 'sakura-app.js'
  // },
  // devServer: {
  //   host: 'localhost',
  //   port: 9000,
  //   hot: true,
  //   disableHostCheck: true,
  //   headers: { 'Access-Control-Allow-Origin': '*' },
  // },
})
