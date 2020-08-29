let Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')

  .addEntry('app', './assets/js/app.js')

  .splitEntryChunks()

  .enableSingleRuntimeChunk()

  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())

  .enableSassLoader()

  .enableIntegrityHashes(Encore.isProduction())

  .autoProvidejQuery()

  .configureFilenames({
    js: 'js/[name].[chunkhash].js',
    css: 'css/[name].[contenthash].css',
    images: 'images/[name].[hash:8].[ext]',
    fonts: 'fonts/[name].[hash:8].[ext]'
  });

module.exports = Encore.getWebpackConfig();
