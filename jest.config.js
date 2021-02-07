module.exports = {
  collectCoverageFrom: [
    '<rootDir>/resources/js/components/**/*.{js,vue}',
  ],
  moduleFileExtensions: [
    'js',
    'json',
    'vue'
  ],
  setupFilesAfterEnv: [
    '<rootDir>/tests/JavaScript/setup.js'
  ],
  transform: {
    '\\.js$': 'babel-jest',
    '\\.vue$': 'vue-jest'
  }
};
