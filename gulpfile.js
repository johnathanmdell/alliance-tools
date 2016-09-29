const elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(mix => {
    mix.sass('app.scss')
        .scripts([
            './node_modules/jquery/dist/jquery.js',
            './node_modules/tether/dist/js/tether.js',
            './node_modules/bootstrap-sass/assets/javascripts/bootstrap.js',
            './node_modules/gentelella/src/js/custom.js',
            './resources/assets/js/app.js',
        ], 'public/js/app.js', './')
        .version([
            'css/app.css',
            'js/app.js'
        ])
        .copy('./node_modules/font-awesome/fonts', 'public/build/fonts');
});