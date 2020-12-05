const mix = require('laravel-mix');

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

mix.react('resources/frontend/js/app.jsx', 'public/frontend/js')
    .sass('resources/frontend/sass/app.scss', 'public/frontend/css');

mix.react('resources/admin/js/app.jsx', 'public/admin/js')
    .sass('resources/admin/sass/app.scss', 'public/admin/css');
