const mix = require('laravel-mix');

require('laravel-mix-tailwind');
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
}

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css')
   .tailwind('./tailwind.config.js')
   .options({
     postCss: [
      require('autoprefixer')
     ]
   });

if (mix.inProduction()) {
  mix
   .version();
}
