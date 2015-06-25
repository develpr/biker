var gulp = require('gulp');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');
var sourcemaps = require('gulp-sourcemaps');
var watch = require('gulp-watch');

gulp.task('default', function() {
  // place code here
});

gulp.task('watch', function() {
	gulp.watch('source/sass/**/*.scss', ['styles']);
});

gulp.task('styles', function() {
	gulp.src('source/sass/**/*.scss')
		.pipe(sourcemaps.init())
		.pipe(sass({
			errLogToConsole: true
		}))
		.pipe(gulp.dest('../public/css/'))
		.pipe(minifyCss())
		.pipe(sourcemaps.write())
		.pipe(gulp.dest('../public/css/'));
});