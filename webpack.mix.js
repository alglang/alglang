/* eslint-disable import/no-extraneous-dependencies */
/* global Mix */
const mix = require('laravel-mix');
const autoprefixer = require('autoprefixer');
const tailwindcss = require('tailwindcss');
const fontDisplay = require('postcss-font-display');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue();

mix.extract([
  'alpinejs',
  'leaflet',
  'vue'
]);

mix.postCss('resources/css/app.css', 'public/css', [
  autoprefixer(),
  tailwindcss(),
  fontDisplay({ display: 'swap', replace: false })
]);

if (mix.inProduction()) {
  mix.version();
}
