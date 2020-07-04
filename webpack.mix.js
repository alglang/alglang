/* eslint-disable import/no-extraneous-dependencies */
/* global Mix */
const mix = require('laravel-mix');
const autoprefixer = require('autoprefixer');
const tailwindcss = require('tailwindcss');

require('laravel-mix-merge-manifest');

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

if (process.env.NODE_ENV === 'testing') {
  Mix.manifest.name = 'mix-manifest.testing.json';
  mix.sourceMaps();
}

mix.js('resources/js/app.js', 'public/js');
mix.postCss('resources/css/app.css', 'public/css', [
  autoprefixer(),
  tailwindcss()
]);

if (mix.inProduction()) {
  mix.version();
}
