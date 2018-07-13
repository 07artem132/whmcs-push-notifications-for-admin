const MinifyPlugin = require("babel-minify-webpack-plugin");
const ProgressBarPlugin = require('progress-bar-webpack-plugin');
const chalk = require('chalk');

module.exports = {
    entry: {
        app: './src/app.js',
        config: './src/config.js',
        'firebase-messaging-sw': './src/firebase-messaging-sw.js'
    },
    output: {path: `${__dirname}/js`, filename: '[name].js'},
    plugins: [
        /*      new MinifyPlugin({
                  booleans: true,
                  builtIns: true,
                  consecutiveAdds: true,
                  deadcode: true,
                  evaluate: true,
                  flipComparisons: true,
                  guards: true,
                  infinity: true,
                  mangle: true,
                  memberExpressions: true,
                  mergeVars: true,
                  numericLiterals: true,
                  propertyLiterals: true,
                  regexpConstructors: true,
                  removeConsole: true,
                  removeUndefined: true,
                  replace: true,
                  simplify: true,
                  simplifyComparisons: true,
                  typeConstructors: true,
                  undefinedToVoid: true
              }, {
                  comments: false
              }),*/
        new ProgressBarPlugin({
            format: '  build [:bar] ' + chalk.green.bold(':percent') + ' (:elapsed seconds)',
            clear: false
        })
    ],
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: 'babel-loader'
            }
        ]
    },
//    devtool: 'source-map'
};
