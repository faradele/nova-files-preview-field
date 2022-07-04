  let mix = require('laravel-mix')
  let path = require('path')

  require('./nova.mix')

  mix
    .setPublicPath('dist')
    .js('resources/js/field.js', 'js')
    .vue({ version: 3 })
    // .sass('resources/sass/field.scss', 'css')
    .alias({
      '@': path.join(__dirname, 'resources/js/'),
    })
    .nova('{{ name }}')
if (mix.inProduction()) {
    mix.version()
}
