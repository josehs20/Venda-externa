const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .scripts('node_modules/jquery/dist/jquery.js', 'public/js/jquery.js')
    .sass('node_modules/bootstrap/scss/bootstrap.scss', 'public/css/bootstrap.css')
    .sass('node_modules/bootstrap-icons/font/bootstrap-icons.scss', 'public/css/bootstrap-icons.css');