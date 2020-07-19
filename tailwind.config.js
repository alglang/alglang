/* eslint-disable import/no-extraneous-dependencies */
const customForms = require('@tailwindcss/custom-forms');

module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/js/components/**/*.vue',
    './resources/css/**/*.css'
  ],
  theme: {
    fontFamily: {
      body: ['Lato', 'sans-serif'],
      display: ['Lato', 'sans-serif']
    },
    extend: {
      listStyleType: {
        square: 'square'
      }
    }
  },
  variants: {
    display: ['responsive', 'group-hover']
  },
  plugins: [
    customForms
  ]
};
