/* eslint-disable import/no-extraneous-dependencies */
const customForms = require('@tailwindcss/custom-forms');
const filters = require('tailwindcss-filters');

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
    filter: {
      none: 'none',
      'brightness-1': 'brightness(1)',
      'brightness-5/4': 'brightness(1.25)'
    },
    extend: {
      listStyleType: {
        square: 'square'
      },
      width: {
        fit: 'fit-content'
      }
    }
  },
  variants: {
    padding: ['responsive', 'first'],
    display: ['responsive', 'group-hover'],
    filter: ['responsive', 'hover', 'focus']
  },
  plugins: [
    customForms,
    filters
  ]
};
