const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config.js");
const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const RemovePlugin = require("remove-files-webpack-plugin");

const isProduction = process.env.NODE_ENV === "production";
const mode = isProduction ? "production" : "development";

defaultConfig.output = {
    ...defaultConfig.output,
    path: path.resolve(process.cwd(), "assets/blocks"),
};

defaultConfig.entry = {
    'index': './src/index.js'
};

const assetsConfig = {
    mode,
    entry: {
        // All blocks editor css including common
        "blocks-editor": [
            // Blocks common editor css
            ...glob.sync("./src/scss/editor.scss"),

            // All blocks editor css
            ...glob.sync("./src/blocks/**/**/*editor.scss"),

            // ALl Components editor css
            ...glob.sync("./src/components/**/**/*editor.scss"),
        ],
        
        // All blocks editor css including common
        "blocks-style": [
            // All blocks style css
            ...glob.sync("./src/blocks/**/**/*style.scss"),
        ],
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: "babel-loader",
                },
            },
            {
                test: /\.scss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: !isProduction,
                            url: false,
                            importLoaders: 1,
                        },
                    },
                    {
                        loader: "postcss-loader",
                        options: {
                            postcssOptions: {
                                ident: "postcss",
                                sourceMap: !isProduction,
                                plugins: ["postcss-preset-env"],
                            },
                        },
                    },
                    {
                        loader: "sass-loader",
                        options: {
                            sourceMap: !isProduction,
                        },
                    },
                ],
            },
        ],
    },
    resolve: {
        extensions: [".css", ".scss"],
    },
    output: {
        // Webpack will create js files even though they are not used
        filename: "[name].js",
        // Where the CSS is saved to
        path: path.resolve(__dirname, "assets/blocks"),
        publicPath: "/assets/blocks",
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "[name].css",
            chunkFilename: "[id].css"
        }),
        new RemovePlugin({
            after: {
                test: [
                    {
                        folder: "assets/blocks",
                        method: (absoluteItemPath) => {
                            return new RegExp(/\-style.js|-editor.js|index.css$/, "m").test(
                                absoluteItemPath
                            );
                        },
                        recursive: false,
                    }
                ],
            },
        }),
    ]
};

if (!isProduction) {
    delete defaultConfig.devtool; // This will disable generating .map file
}

module.exports = [defaultConfig, assetsConfig];
module.exports.parallelism = 1;
