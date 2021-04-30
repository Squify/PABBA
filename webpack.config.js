const Encore = require('@symfony/webpack-encore');
const path = require('path');
var webpack = require('webpack');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addEntry('map', './assets/js/map.js')
    .addEntry('searchTutorial', './assets/js/tutorial_search.js')
    .addEntry('tutorial', './assets/js/tutorial.js')
    // .addStyleEntry('mapStyle', './assets/scss/map.scss')
    .addStyleEntry('login', './assets/scss/login.scss')
    .addStyleEntry('base', './assets/scss/base.scss')

    .copyFiles([
        {from: './node_modules/ckeditor/', to: 'ckeditor/[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false},
        {from: './node_modules/ckeditor/adapters', to: 'ckeditor/adapters/[path][name].[ext]'},
        {from: './node_modules/ckeditor/lang', to: 'ckeditor/lang/[path][name].[ext]'},
        {from: './node_modules/ckeditor/plugins', to: 'ckeditor/plugins/[path][name].[ext]'},
        {from: './node_modules/ckeditor/skins', to: 'ckeditor/skins/[path][name].[ext]'}
    ])

    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })

    .addAliases({
        prestaimage: path.resolve(__dirname, 'public/bundles/prestaimage')
    })

    .enableSassLoader()
    .enableIntegrityHashes(Encore.isProduction())
    .autoProvidejQuery()
    .addPlugin(new webpack.ProvidePlugin({
        $: 'jquery',
        jQuery: 'jquery',
        'window.jQuery': 'jquery',
    }))
;

module.exports = Encore.getWebpackConfig();
