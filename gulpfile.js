// gulp-sourcemaps (installed - need to configure)
//gulp-responsive (installed - need to configure)

'use strict';

// Project related
var project = 'grg', // Project name, used for build zip.
    projectURL = 'grg.local', // Local Development URL for BrowserSync. Change as-needed.
    build = './ship', // Files that you want to package into a zip go here
    buildInclude = [
      //include common files
      '**/*.php',
      '**/*.html',
      '**/*.css',
      '**/*.svg',


			// include specific files and folders
			'screenshot.png',

			// exclude files and folders
			'!node_modules/**/*',
			'!style.css.map'
					          ];

var gulp = require('gulp'),
    gulpLoadPlugins = require('gulp-load-plugins'),
    gulpLoadTasks = require('gulp-load-tasks'),
    plugins = gulpLoadPlugins(),
    browserSync = require('browser-sync').create(),
    path = require('path'),
    reload = browserSync.reload;

// Put JS files into array
var jsFileList = [
  'assets/js/vendor/*.js',
  'assets/js/main.js'
];

gulpLoadPlugins({
    DEBUG: false, // when set to true, the plugin will log info to console. Useful for bug reporting and issue debugging
    pattern: ['gulp-*', 'gulp.*'], // the glob(s) to search for
    scope: ['dependencies', 'devDependencies', 'peerDependencies'], // which keys in the config to look within
    replaceString: /^gulp(-|\.)/, // what to remove from the name of the module when adding it to the context
    camelize: true, // if true, transforms hyphenated plugins names to camel case
    lazy: true // whether the plugins should be lazy loaded on demand
});

gulp.task('bs', function() {
    browserSync.init({
      proxy: projectURL,
      port: 3030,
      open: true,
      injectChanges: true
    });

    gulp.watch('assets/scss/**/*.scss', ['sass']);
    gulp.watch('assets/scss/**/*.scss').on('change', browserSync.reload);
    gulp.watch('**/*.php').on('change', browserSync.reload);
    gulp.watch('views/**/*.twig').on('change', browserSync.reload);
});

gulp.task('sass', function() {
  return gulp.src('assets/scss/main.scss')
    //.pipe(plugins.sourcemaps.init() )
    .pipe(plugins.sass())
    .on('error', console.error.bind(console))
    //.pipe(plugins.sourcemaps.write( { includeContent: false } ) )
    //.pipe(plugins.sourcemaps.init( { loadMaps: true } ) )
    .pipe(plugins.autoprefixer({
        browsers: ['last 5 versions']
        }))
    //.pipe(plugins.sourcemaps.write ( 'assets/scss' ) )
    .pipe(gulp.dest('assets/css'))
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.cssnano())
    .pipe(plugins.plumber())
    .pipe(gulp.dest('assets/css'))
    .pipe(plugins.notify( { message: 'TASK: styles completed! 💯', onLast: true } ) )
    //.pipe(plugins.livereload());
});

gulp.task('js', function() {
  return gulp.src(jsFileList)
    .pipe(plugins.plumber())
    .pipe(plugins.concat('scripts.js'))
    .pipe(gulp.dest('assets/js/build'))
    .pipe(plugins.uglify())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.notify( { message: 'TASK: JS completed! 💯', onLast: true } ) )
    .pipe(gulp.dest('assets/js/build'));
});

gulp.task('js-head', function() {
  return gulp.src('assets/js/vendor-head/*.js')
    .pipe(plugins.plumber())
    .pipe(plugins.concat('scripts-head.js'))
    .pipe(gulp.dest('assets/js/build'))
    .pipe(plugins.uglify())
    .pipe(plugins.rename({suffix: '.min'}))
    .pipe(plugins.notify( { message: 'TASK: headJs completed! 💯', onLast: true } ) )
    .pipe(gulp.dest('assets/js/build'));
});

gulp.task('svgs', function () {
  return gulp.src('assets/img/svg/*.svg')
    .pipe(plugins.plumber())
    .pipe(plugins.rename({prefix: 'shape-'}))
    .pipe(plugins.svgmin(function (file) {
        var prefix = path.basename(file.relative, path.extname(file.relative));
        return {
          plugins: [{
            cleanupIDs: {
              prefix: prefix + '-',
              minify: true
            }
          }]
        }
    }))
    .pipe(plugins.svgstore())
    .pipe(plugins.rename('svg-defs.svg'))
    .pipe(plugins.notify( { message: 'TASK: "SVGs" Completed! 💯', onLast: true } ) )
    .pipe(gulp.dest('views/utility'));
});

gulp.task('clear', function () {
  plugins.cache.clearAll();
});


gulp.task('watch', function() {
    gulp.watch('assets/scss/**/*.scss', ['sass']);
    gulp.watch('assets/js/**/*.js', ['scripts']);
    gulp.watch('**/*.php').on('change', browserSync.reload);
});

gulp.task('default', ['scripts', 'sass', 'watch']);
