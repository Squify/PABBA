const Encore = require('@symfony/webpack-encore');
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
.setOutputPath('public/build/')
.setPublicPath('/build')

.addEntry('app', './assets/js/app.js')
.addStyleEntry('login', './assets/scss/login.scss')

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
;

module.exports = Encore.getWebpackConfig();