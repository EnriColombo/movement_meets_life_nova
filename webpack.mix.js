const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

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

// Compile SCSS and JS
/*mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]);*/

/*mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
        .options({
            processCssUrls: false,
            postCss: [ tailwindcss('./tailwind.config.js') ],
        });*/

mix.sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],
    });

// Vendor extraction - https://laravel.com/docs/8.x/mix#vendor-extraction
mix.js('resources/js/app.js', 'public/js')
    .extract([
        'jquery',
        'justifiedGallery',
        '@fancyapps/fancybox',
    ]);

// Copy the image and font files to the public folder - https://laracasts.com/discuss/channels/elixir/laravel-mix-image-workflow
mix.copy( 'resources/images', 'public/images', false );
mix.copy( 'resources/webfonts', 'public/webfonts', false );

// Sync browser any time something change in compiled css, js or views
mix.browserSync({
    proxy: 'laravel_jetstream.test',
    host: 'laravel_jetstream.test',
    notify: false,
    files: [
        './app/**/*',
        './routes/**/*',
        './public/css/*.css',
        './public/js/*.js',
        './resources/views/**/*.blade.php',
        './resources/lang/**/*',
    ],
})