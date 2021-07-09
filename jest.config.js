module.exports = {
  preset: 'ts-jest',
  testEnvironment: 'jsdom',
  moduleNameMapper: {
    '@/(.*)$': '<rootDir>/src/$1',
    // '^vue$': 'vue/dist/vue.esm-bundler.js',
  },
  transform: {
    '^.+\\.vue$': 'vue-jest',
    // '^.+\\js$': 'babel-jest',
    '^.+\\.(t|j)sx?$': 'ts-jest',
  },
}
