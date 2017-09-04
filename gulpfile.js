var gulp = require('gulp');
var sass = require('gulp-sass');
var browserSync = require('browser-sync').create();
var header = require('gulp-header');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var uglify = require('gulp-uglify');
var clean = require('gulp-rimraf');
var cachebust = require('gulp-cache-bust');
var ftp = require('vinyl-ftp');
var gutil = require('gulp-util');
var pkg = require('./package.json');
var changed = require('gulp-changed');

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
    return gulp.src(['js/agency.js', 'js/contact_me.js', 'js/jqBootstrapValidation.js', 'anmalan/anmalan.js'])
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


// Copy vendor libraries from /node_modules into /vendor
gulp.task('copy-dist', [], function() {
    gulp.src(['vendor/font-awesome/fonts/**'])
        .pipe(gulp.dest('dist/vendor/font-awesome/fonts'));
    gulp.src(['vendor/bootstrap-sass/assets/javascripts/bootstrap.min.js'])
        .pipe(gulp.dest('dist/vendor/bootstrap-sass/assets/javascripts'));
    gulp.src(['vendor/jquery/dist/jquery.min.js'])
        .pipe(gulp.dest('dist/vendor/jquery/dist'));
    gulp.src(['vendor/jquery.easing/js/jquery.easing.min.js'])
        .pipe(gulp.dest('dist/vendor/jquery.easing/js'));
    gulp.src(['vendor/composer/**/*'])
        .pipe(gulp.dest('dist/vendor/composer'));
    gulp.src(['vendor/phpmailer/**/*'])
        .pipe(gulp.dest('dist/vendor/phpmailer'));
    gulp.src(['vendor/autoload.php'])
        .pipe(gulp.dest('dist/vendor'));
    gulp.src([
        '!dist/**',
        '!node_modules/**',
        '!vendor/**',
        'favicon*',
        '**/*.min.css',
        '**/*.min.js',
        '**/*.php',
        '**/img/**',
        '**/*.pdf',
        '**/*.html'
    ])
        // .pipe(changed('dist'))
        .pipe(gulp.dest('dist'));
    gulp.src(['dist/**/*.html'])
        .pipe(cachebust({type: 'timestamp'}))
        .pipe(gulp.dest('dist'));
});

// Deploy to ftp
gulp.task('deploy', ['copy-dist'], function() {
    var conn = ftp.create({
        host: 'ftp.dressbyheart.se',
        user: 'tyllnsro',
        password: 'T9ec209eLv',
        parallell: 10,
        log: gutil.log
    });

    var globs = ['**'];

    return gulp.src(globs, { cwd: './dist', base: './dist', buffer: false })
        .pipe(conn.newer('/public_html'))
        .pipe(conn.dest('/public_html'));
});
