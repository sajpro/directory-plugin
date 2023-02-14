const defaultConfig = require("./node_modules/@wordpress/scripts/config/webpack.config.js");
const path = require("path");
const glob = require("glob");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const RemovePlugin = require("remove-files-webpack-plugin");

const isProduction = process.env.NODE_ENV === "production";
const mode = isProduction ? "production" : "development";

defaultConfig.output = {
    ...defaultConfig.output,
    path: path.resolve(process.cwd(), "build"),
};

defaultConfig.entry = {
    'index': './src/index.js',
    // 'admin/js/index': './src/admin/index.js',
};

const assetsConfig = {
    mode,
    entry: {
		// 'admin/css/style': './src/admin/style.scss',

        // // All blocks editor css including common
        "blocks-editor": [
            // Block common editor css
            ...glob.sync("./src/scss/editor.scss"),

            // All blocks editor css
            ...glob.sync("./src/blocks/**/**/*editor.scss"),
        ],
        // // All blocks editor css including common
        "blocks-style": [
            // All blocks editor css
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
        // filename: "[name].useless.js",
        // chunkFilename: "[name].[chunkhash].useless.js",

        // Where the CSS is saved to
        path: path.resolve(__dirname, "build"),
        publicPath: "/build",
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
                        folder: "build",
                        method: (absoluteItemPath) => {
                            // return new RegExp(/\.useless.js$/, 'm').test(absoluteItemPath);
                            return new RegExp(/\-style.js|-editor.js|index.css$/, "m").test(
                                absoluteItemPath
                            );
                        },
                        recursive: false,
                    },
                    // {
                    //     folder: "build/admin/css",
                    //     method: (absoluteItemPath) => {
                    //         // return new RegExp(/\.useless.js$/, 'm').test(absoluteItemPath);
                    //         return new RegExp(/\.js$/, "m").test(
                    //             absoluteItemPath
                    //         );
                    //     },
                    //     recursive: true,
                    // }
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
