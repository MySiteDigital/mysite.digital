const path = require('path');

// include the js minification plugin
const UglifyJSPlugin = require('uglifyjs-webpack-plugin');

// include the css extraction and minification plugins
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

const devMode = (process.argv.slice().pop() !== 'production');

module.exports = {
    entry: {
       theme: './src/js/index.js'
    },
    output: {
       path: path.resolve(__dirname),
       filename: devMode ? '[name].js' : '../assets/js/[name].js',
    },
    watch: devMode,
    optimization: {
        minimizer: [
            new UglifyJSPlugin(
                {
                    cache: true,
                    parallel: true,
                    sourceMap: devMode
                }
            ),
            new OptimizeCSSAssetsPlugin({})
        ]
    },
    plugins: [
        // extract css into dedicated file
        new MiniCssExtractPlugin(
            {
                filename: devMode ? 'css/[name].css' : '../assets/css/[name].css'
            }
        ),
    ],
    module: {
        rules: [
            // perform js babelization on all .js files
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                    options: {
                        presets: ['babel-preset-env']
                    }
                }
            },
            {
                test: /\.css?$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader'
                ]
            },
            {
                test: /\.(sass|scss)$/,
                use: [
                        {
                            loader: devMode ? "style-loader" : MiniCssExtractPlugin.loader,
                        },
                        {
                            loader: "css-loader",
                            options: {
                                sourceMap: true
                            }
                        },
                        {
                            loader: "sass-loader",
                            options: {
                                sourceMap: true
                            }
                        }
                ]
            }
        ]
    },
    devServer: {
        // Display only errors to reduce the amount of output.
        //stats: "errors-only",
        host: process.env.HOST, // Defaults to `localhost`
        port: 3000, // Defaults to 8080
        open: false, // Open the page in browser
        disableHostCheck: true
    },
};
