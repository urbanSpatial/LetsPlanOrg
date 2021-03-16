module.exports = {
  ignorePatterns: ['node_modules/', 'vendor/', 'public/'],
  env: {
    browser: true,
    node: true,
  },
  extends: [
    'plugin:vue/recommended',
    'airbnb-base',
  ],
  parserOptions: {
    sourceType: 'module',
  },
  plugins: [
    'vue',
  ],
  globals: {
    route: true, // Ziggy
  },
};
