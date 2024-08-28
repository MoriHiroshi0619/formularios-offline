const mix = require('laravel-mix');

//bootstrap + sass
mix.sass('resources/sass/app.scss', 'public/css')
//jquey
mix.js('resources/js/app.js', 'public/js')
    .css('resources/css/app.css', 'public/css', [
        //
    ]).version();
// mix.js('resources/jsrun/app.js', 'public/js')
//     .postCss('resources/css/app.css', 'public/css', [
//
//     ]);
