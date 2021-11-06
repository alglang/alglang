/* eslint-disable import/no-extraneous-dependencies */
const plugin = require('tailwindcss/plugin');
const customForms = require('@tailwindcss/custom-forms');
const filters = require('tailwindcss-filters');
const scrollbars = require('tailwind-scrollbar');

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
      },
      maxHeight: {
        md: '28rem',
        56: '14rem'
      },
      zIndex: {
        '-10': '-10'
      }
    }
  },
  variants: {
    backgroundColor: ['responsive', 'even', 'odd', 'hover', 'focus', 'disabled'],
    padding: ['responsive', 'first'],
    display: ['responsive', 'group-hover', 'group-focus-within'],
    filter: ['responsive', 'hover', 'focus'],
    cursor: ['responsive', 'disabled'],
    textColor: ['responsive', 'hover', 'focus', 'disabled'],
    scale: ['responsive', 'hover', 'focus', 'active', 'group-hover', 'group-focus-within'],
    boxShadow: ['responsive', 'hover', 'focus', 'disabled'],
    zIndex: ['focus']
  },
  plugins: [
    customForms,
    filters,
    scrollbars,
    plugin(function ({ addVariant, e }) {
      addVariant('group-focus-within', ({ modifySelectors, separator }) => {
        modifySelectors(({ className }) => `.group:focus-within .${e(`group-focus-within${separator}${className}`)}`);
      });
    })
  ],
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true
  }
};
