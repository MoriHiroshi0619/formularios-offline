const mix = require('laravel-mix');

//bootstrap + sass
mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/projeto.scss', 'public/css')
    .sass('resources/sass/fonts.scss', 'public/css')
    .version();
//jquey + funções globais
mix.js('resources/js/app.js', 'public/js')
    .copy('node_modules/chart.js/dist/chart.umd.js', 'public/js/chart.js') //Criação de gráficos
    .copy('node_modules/chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.js', 'public/js/chartjs-plugin-datalabels.js') //plugins para calcular a porcentagem no gráfico
    .copy('node_modules/wordcloud/src/wordcloud2.js', 'public/js/wordcloud2.js') //Criação de nuvem de palavras
    .js('resources/js/functions.js', 'public/js')
    .css('resources/css/app.css', 'public/css', [
        //
    ]).version();

