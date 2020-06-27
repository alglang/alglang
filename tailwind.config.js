module.exports = {
  purge: [
    './resources/views/**/*.blade.php',
    './resources/css/**/*.css',
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
  variants: {},
  plugins: [
    require('@tailwindcss/custom-forms')
  ]
}
