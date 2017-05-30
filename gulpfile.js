var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var header = require('gulp-header');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var clean = require('gulp-rimraf');
var pkg = require('./package.json');

// Set the banner content
var banner = ['/*!\n',
    ' * Start Bootstrap - <%= pkg.title %> v<%= pkg.version %> (<%= pkg.homepage %>)\n',
    ' * Copyright 2013-' + (new Date()).getFullYear(), ' <%= pkg.author %>\n',
    ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n',
    ' */\n',
    ''
].join('');

// Minify compiled CSS
gulp.task('minify-css', ['sass'], function() {
    return gulp.src('css/agency.css')
        .pipe(cleanCSS({ compatibility: 'ie8' }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('css'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

// Minify JS
gulp.task('minify-js', function() {
    return gulp.src(['js/agency.js', 'js/contact_me.js', 'js/jqBootstrapValidation.js'])
        .pipe(uglify())
        .pipe(header(banner, { pkg: pkg }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest('js'))
        .pipe(browserSync.reload({
            stream: true
        }));
});

gulp.task('clean', [], function() {
  return gulp.src("dist/*", { read: false }).pipe(clean());
});

// Copy vendor libraries from /node_modules into /vendor
gulp.task('copy-dist', ['clean'], function() {
    gulp.src(['vendor/font-awesome/fonts/**'])
        .pipe(gulp.dest('dist/vendor/font-awesome/fonts'));
    gulp.src(['vendor/bootstrap-sass/assets/javascripts/bootstrap.min.js'])
        .pipe(gulp.dest('dist/vendor/bootstrap-sass/assets/javascripts'));
    gulp.src(['vendor/jquery/dist/jquery.min.js'])
        .pipe(gulp.dest('dist/vendor/jquery/dist'));
    gulp.src(['vendor/jquery.easing/js/jquery.easing.min.js'])
        .pipe(gulp.dest('dist/vendor/jquery.easing/js'));
    gulp.src([
        '**/*.html',
        'favicon*',
        '**/*.min.css',
        '**/*.min.js',
        '**/*.php',
        '**/img/**',
        '!node_modules/**',
        '!vendor/**'
    ]).pipe(gulp.dest('dist'));
});

// Run everything
gulp.task('default', ['sass', 'minify-css', 'minify-js', 'copy']);

// Configure the browserSync task
gulp.task('browserSync', function() {
    browserSync.init({
        server: {
            baseDir: ''
        },
    });
});

// Dev task with browserSync
gulp.task('dev', ['browserSync', 'sass', 'minify-css', 'minify-js'], function() {
    gulp.watch('scss/*.scss', ['sass']);
    gulp.watch('css/*.css', ['minify-css']);
    gulp.watch('js/*.js', ['minify-js']);
    // Reloads the browser whenever HTML or JS files change
    gulp.watch('*.html', browserSync.reload);
    gulp.watch('js/**/*.js', browserSync.reload);
});

// Compiles SCSS files from /scss into /css
gulp.task('sass', function() {
    return gulp.src('scss/agency.scss')
        .pipe(sass())
        .pipe(header(banner, { pkg: pkg }))
        .pipe(gulp.dest('css'))
        .pipe(browserSync.reload({
            stream: true
        }));
});
