let mix = require('laravel-mix')

const ASSET_URL = process.env.ASSET_URL && process.env.ASSET_URL + "/" || '/';
mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.DefinePlugin({
                "process.env.ASSET_PATH": JSON.stringify(ASSET_URL)
            })
        ]
    };
});

mix
  .setPublicPath('dist')
  .js('resources/js/field.js', 'js')
  .sass('resources/sass/field.scss', 'css')

  if (mix.inProduction()) {
    mix.version()
  }
