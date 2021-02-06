module.exports = {
  collectCoverageFrom: [
    '<rootDir>/resources/js/**.*.js',
    '<rootDir>/resources/js/**.*.vue',
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
