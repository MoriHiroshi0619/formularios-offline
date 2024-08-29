const mix = require('laravel-mix');

//bootstrap + sass
mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/projeto.scss', 'public/css')
    .sass('resources/sass/fonts.scss', 'public/css')
    .version();
//jquey + funções globais
mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/functions.js', 'public/js')
    .css('resources/css/app.css', 'public/css', [
        //
    ]).version();

