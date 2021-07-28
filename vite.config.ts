import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
// import legacy from '@vitejs/plugin-legacy'
import path from 'path'
import svgicon from 'vite-plugin-svgicon'
// import viteStylelint from '@amatlash/vite-plugin-stylelint'

const target = process.env.APP_TARGET

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
    // viteStylelint(), // bugly
  ],
  server: {
    host: '0.0.0.0',
    cors: true,
    strictPort: true,
    port: 9000,
    hmr: {
      // TODO: .env
      protocol: 'ws',
      host: 'localhost',
      port: 9000,
    },
    fs: {
      // This maybe MDC's incorrect absolute path
      allow: ['./', './node_modules/', '/node_modules/'],
    },
  },
  build: {
    target: 'modules',
    manifest: true,
    sourcemap: true,
    outDir: target === 'main' ? 'app/assets/main' : 'app/assets/admin',
    chunkSizeWarningLimit: 2048,
    // minify: false, // set false if use flex-gap-polyfill
    rollupOptions: {
      // external: ['vue'],
      input: [path.resolve(__dirname, target === 'main' ? 'src/main.ts' : 'src/admin/main.ts')],
      output: {
        // TODO: use ES5 bundle instead
        // globals: {
        //   vue: 'Vue',
        // },
        // manualChunks: undefined,
      },
    },
  },
  optimizeDeps: {
    include: [
      'gsap',
      'marked',
      'gsap/ScrollTrigger',
      'highlight.js',
      '@vueuse/core',
      'perfect-scrollbar',
    ],
  },
  css: {
    preprocessorOptions: {
      scss: {
        sassOptions: { quietDeps: true },
      },
    },
  },
})
