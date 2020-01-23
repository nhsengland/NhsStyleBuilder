module.exports = {
  plugins: [
    require('postcss-node-sass'),
    // require('postcss-color-function'),
    require('postcss-sass-extend'),
    require('lost'),
    require('autoprefixer')
  ]
};
