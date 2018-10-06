var Encore = require('@symfony/webpack-encore');
var SpriteLoaderPlugin = require('svg-sprite-loader/plugin');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
     */
    .addEntry('app', './assets/js/app.js')
    .createSharedEntry('vendor', [
        'vue',
        'lodash'
    ])

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())
    .disableImagesLoader()
    .addLoader({
        test: /\.(png|jpg|jpeg|gif|ico|svg|webp)$/,
        loader: 'file-loader',
        exclude: [/svg\/.+\.svg$/],
        options: {
            name: 'images/[name].[hash:8].[ext]',
        }
    })
    .addLoader({
        test: /svg\/.+\.svg$/,
        loader: 'svg-sprite-loader',
        options: {
            extract: true,
            spriteFilename: 'images/sprites.svg'
        }
    })
    .addPlugin(new SpriteLoaderPlugin({
        plainSprite: true
    }))
    .enableSassLoader()
    .enableVueLoader()
;

module.exports = Encore.getWebpackConfig();
