const resolve = require('path').resolve;
const ExtractTextPlugin = require('extract-text-webpack-plugin');

const style = new ExtractTextPlugin("style.css");
const ie9style = new ExtractTextPlugin("ie9.css");

module.exports = {
  context: resolve('css/src'),
  entry: './entry.js',
  output: {
    path: resolve('css/dist'),
    filename: 'bundle.js',
    publicPath: '/css/dist/',
  },
  module: {
    rules: [
      {
        test: /layoutblock_style\.css$/,
        exclude: /node_modules/,
        use: style.extract({
          fallback: 'style-loader',
          use: [
            // {
            //   loader: 'css-loader',
            //   options: {
            //     importLoaders: 1,
            //   }
            // },
            {
              loader: 'postcss-loader'
            }
          ]
        })
      },
      {
        test: /ie9fix\.css$/,
        exclude: /node_modules/,
        use: ie9style.extract({
          fallback: 'style-loader',
          use: [
            // {
            //   loader: 'css-loader',
            //   options: {
            //     importLoaders: 1,
            //   }
            // },
            {
              loader: 'postcss-loader'
            }
          ]
        })
      }
    ]
  },
  plugins: [style, ie9style]
};
