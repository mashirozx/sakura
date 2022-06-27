
var fs = require('fs'),
    gulp = require('gulp'),
    sass = require('gulp-sass'),
    banner = require('gulp-banner'),
    rename = require('gulp-rename'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    pkg = require('./package.json'),
    htmlclean = require('htmlclean'),
    replace = require('gulp-replace'),
    minify = require('gulp-clean-css');

function escape (text) {
  return text.replace(/'/g, "\\'").replace(/"/g, "\\\"");
}

function htmlTemplate() {
  return replace('BOTUI_TEMPLATE', escape(
    htmlclean(fs.readFileSync('./src/botui.html', 'utf8'))
  ));
}

var comment = '/*\n' +
    ' * <%= pkg.name %> <%= pkg.version %>\n' +
    ' * <%= pkg.description %>\n' +
    ' * <%= pkg.homepage %>\n' +
    ' *\n' +
    ' * Copyright <%= year %>, <%= pkg.author.name %>\n' +
    ' * Released under the <%= pkg.license %> license.\n' +
    '*/\n\n';

gulp.task('styles', function() {
  gulp.src(['./src/styles/normal.scss',
            './src/styles/botui.scss'])
      .pipe(sass().on('error', sass.logError))
      .pipe(minify())
      .pipe(concat('botui.min.css'))
      .pipe(banner(comment, {
        pkg: pkg,
        year: new Date().getFullYear()
      }))
      .pipe(gulp.dest('./build/'));
});

gulp.task('themes', function() {
  gulp.src('./src/styles/themes/*.scss')
      .pipe(sass().on('error', sass.logError))
      .pipe(minify())
      .pipe(rename(function (path) {
        path.basename = 'botui-theme-' + path.basename;
      }))
      .pipe(gulp.dest('./build/'));
});

gulp.task('scripts', function () {
      gulp.src('./src/scripts/botui.js') // simply copy the original one
      .pipe(htmlTemplate())
      .pipe(banner(comment, {
        pkg: pkg,
        year: new Date().getFullYear()
      }))
      .pipe(gulp.dest('./build/'));

      gulp.src('./src/scripts/botui.js')  // minified version
      .pipe(uglify())
      .pipe(htmlTemplate())
      .pipe(rename('botui.min.js'))
      .pipe(banner(comment, {
        pkg: pkg,
        year: new Date().getFullYear()
      }))
      .pipe(gulp.dest('./build/'));
});

gulp.task('watch',function() {
  gulp.watch('./src/styles/*.scss', ['styles']);
  gulp.watch('./src/styles/themes/*.scss', ['themes']);
  gulp.watch(['./src/scripts/botui.js', './src/botui.html'], ['scripts']);
});

gulp.task('default', ['styles', 'scripts', 'themes']);
