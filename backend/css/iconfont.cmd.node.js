// Run manually
var fs = require('fs');

require('icon.font')({ // Default options
  fontName: 'iconfont',
  src: './iconfonts/icons',
  dest: './iconfonts',
  configFile: './iconfonts/configs/_icon-config.json',
  saveConfig: true,
  image: false,
  html: false,
  // htmlTemplate: '<module>/templates/html.hbs',
  outputHtml: false,
  css: true,
  cssTemplate: './iconfonts/configs/css.hbs',
  outputCss: true,
  fixedWidth: false,
  normalize: true,
  centerHorizontally: true,
  prependUnicode: true,
  silent: false,
  types: ['woff', 'ttf', 'eot'], // default, 'woff2' and 'svg' are available
  templateOptions: {
      classPrefix: '_icon-',
      baseSelector: '._icon',
      baseClassname: '_icon',
  },
  codepointRanges: [
      [97,122], // a-z
      [65,90], // A-Z
      [48,57], // 0-9
      [0xe001, Infinity]
  ]
}).then(function(){
  var iconfontFilePath = './iconfonts/iconfont.css';
  fs.readFile(iconfontFilePath, 'utf8', function (err, data) {
    if (err) {
      return console.log(err);
    }
    data = data.replace(/"iconfont\./gm, '"../iconfonts/iconfont.');
    fs.writeFile(iconfontFilePath, data, 'utf8', function (err) {
        if (err) return console.log(err);
        console.log('Done. Path in iconftont.css is also updated.');
    });
  });
});