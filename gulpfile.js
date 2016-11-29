var elixir = require('laravel-elixir');
elixir.config.sourcemaps = false;

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

elixir(function(mix) {
    mix
        .sass([
            /*
                Currently in here (things that need other assets publishing):
                    * bootstrap
                    * font-awesome
                    * flag-icon
             */
            'app.scss',
        ], 'resources/assets/generated/sass.css')
        .styles([
            '../generated/sass.css',
            '../bower/bootstrap-social/bootstrap-social.css',
            '../bower/tablesorter/dist/css/theme.bootstrap.min.css',
            '../bower/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css',
            '../bower/typeahead.js-bootstrap3.less/typeaheadjs.css',
            'styles.css'
        ])
        .scripts([
            '../bower/jquery/dist/jquery.js',
            '../bower/bootstrap/dist/js/bootstrap.js',
            '../bower/tablesorter/dist/js/jquery.tablesorter.js',
            '../bower/tablesorter/dist/js/jquery.tablesorter.widgets.js',
            '../bower/moment/min/moment.min.js',
            '../bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
            '../bower/Sortable/jquery.fn.sortable.js',
            '../bower/typeahead.js/dist/typeahead.jquery.js',
            '../bower/typeahead.js/dist/bloodhound.js',
        ])
        .copy('resources/assets/bower/bootstrap-sass/assets/fonts/bootstrap', 'public/vendor/fonts')
        .copy('resources/assets/bower/font-awesome/fonts', 'public/vendor/fonts')
        .copy('resources/assets/bower/flag-icon-css/flags', 'public/vendor/flags')
        .version([
            'css/all.css',
            'js/all.js'
        ]);

});
