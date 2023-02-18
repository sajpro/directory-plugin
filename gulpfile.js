const gulp = require("gulp");
const zip = require("gulp-zip");

const bundle = () => {
    return gulp.src([
        "**/*",
        "!node_modules/**",
        "!src/**",
        "!bundled/**",
        "!.editorconfig",
        "!.gitignore",
        "!composer.lock",
        "!gulpfile.js",
        "!package-lock.json",
        "!package.json",
        "!phpcs.xml.dist",
        "!webpack.config.js",
    ])
    .pipe(zip('directory-plugin.zip'))
    .pipe(gulp.dest("bundled"));
}

exports.bundle = bundle;