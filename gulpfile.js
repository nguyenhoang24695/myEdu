var elixir = require('laravel-elixir');
var fs = require('fs');
var sass = require('gulp-sass');
var gulp = require('gulp');

gulp.task('compile_sass', function() {
    //elixir.Task.find('sass').run();
    for(var i in elixir.tasks){
        if(elixir.tasks[i].name == 'sass'){
            elixir.tasks[i].run();
        }
    }
});

elixir(function(mix) {
        //////////WATCH CHANGE///////
    //mix.task('compile_sass', 'resources/assets/frontend/sass/bootswatch/unibee/*');
    mix.task('compile_sass', 'resources/assets/frontend/sass/bootswatch/myedu/*');
    //mix.task('compile_sass', 'resources/assets/frontend/sass/bootswatch/alphacity/*');
    mix.task('compile_sass', 'resources/assets/frontend/sass/bootswatch/custom/*');
    mix.task('compile_sass', 'resources/assets/frontend/adsv1/*.scss');


    /////////////FRONTEND//////////////////////
    mix.copy('resources/assets/frontend/css', 'public_html/frontend/css')
        .copy('resources/assets/frontend/js', 'public_html/frontend/js')
        .copy('resources/assets/frontend/img', 'public_html/frontend/img')
        .copy('resources/assets/frontend/plugin', 'public_html/frontend/plugin')
        .copy('resources/assets/frontend/fonts', 'public_html/frontend/fonts')
        .copy('resources/assets/frontend/adsv1/font_icon', 'public_html/adsv1/font_icon');

    //ads
    mix.sass([
        '../frontend/adsv1/common.scss',
        '../frontend/adsv1/template1.scss'

    ], 'public_html/adsv1/template1.css')
        .scripts([ //
            '../frontend/adsv1/template1.js'
        ], './public_html/adsv1/template1.js')
        .scripts([ //
            '../frontend/adsv1/colombo_ad_v1.js'
        ], './public_html/adsv1/colombo_ad_v1.js');

    //Unibee
    //mix.sass([
    //    '../frontend/sass/bootswatch/unibee/build.scss',
    //    '../frontend/sass/bootswatch/custom/unibee/private.scss'
    //
    //], 'public_html/frontend/css/unibee.css');

    //Quochoc
    //mix.sass([
    //    '../frontend/sass/bootswatch/quochoc/build.scss',
    //    '../frontend/sass/bootswatch/custom/quochoc/private.scss'
    //
    //], 'public_html/frontend/css/quochoc.css');

    //Ubclass
    mix.sass([
        '../frontend/sass/bootswatch/myedu/build.scss',
        '../frontend/sass/bootswatch/custom/myedu/private.scss'

    ], 'public_html/frontend/css/myedu.css');

    //Alphacity
    //mix.sass([
    //    '../frontend/sass/bootswatch/alphacity/build.scss',
    //    '../frontend/sass/bootswatch/custom/alphacity/private.scss'
    //
    //], 'public_html/frontend/css/alphacity.css');

    /////////////Backend//////////////////////

    mix
        // Copy webfont files from /vendor directories to /public_html directory.
        .copy('resources/assets/backend/css', './public_html/backend/css')
        .copy('resources/assets/backend/js', './public_html/backend/js')
        .copy('resources/assets/backend/img', './public_html/backend/img')
        .copy('resources/assets/backend/plugin', './public_html/backend/plugin')

        .sass([ // Process back-end stylesheets
            '../backend/sass/main.scss',
            '../backend/sass/skin.scss'
        ], './public_html/backend/css/backend.css')
        .scripts([ // Combine back-end scripts
                '../backend/plugin/plugins.js',
                '../backend/js/main.js'
            ], './public_html/backend/js/backend.js')

        // Apply version control
        //.version([
        //    "../public_html/backend/css/backend.css", "../public_html/backend/js/backend.js"]);
});
