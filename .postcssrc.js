module.exports = ({ env, options }) => ({
  ...options,
  plugins: [
    require('autoprefixer')({}),
    // require('flex-gap-polyfill')() // bugly with vite
    // require('stylelint')({}), //
  ],
})
