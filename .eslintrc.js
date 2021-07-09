module.exports = {
  env: {
    browser: true,
    es2021: true,
  },
  extends: [
    'plugin:vue/essential',
    'eslint:recommended',
    '@vue/typescript/recommended',
    '@vue/prettier',
    '@vue/prettier/@typescript-eslint',
  ],
  parserOptions: {
    ecmaVersion: 12,
    parser: '@typescript-eslint/parser',
    sourceType: 'module',
  },
  plugins: ['vue', '@typescript-eslint', 'file-progress', 'formatjs'],
  rules: {
    'formatjs/no-offset': 'error',
    'file-progress/activate': 1,
    indent: ['error', 2, { SwitchCase: 1 }],
    'linebreak-style': ['error', 'unix'],
    'array-element-newline': ['error', { multiline: true, minItems: 3 }],
    quotes: ['error', 'single'],
    semi: ['error', 'never'],
    '@typescript-eslint/no-explicit-any': 0,
    '@typescript-eslint/ban-types': 0,
    'vue/no-multiple-template-root': 0,
    'prettier/prettier': [
      'error',
      {
        endOfLine: 'lf',
      },
    ],
  },
  globals: {
    Pagination: 'readonly',
    WPPostAbstract: 'readonly',
    Post: 'readonly',
    PostListData: 'readonly',
    PostStore: 'readonly',
  },
}
