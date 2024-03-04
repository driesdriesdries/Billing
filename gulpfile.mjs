// Import ES modules
import gulp from 'gulp';
import sassModule from 'gulp-sass';
import autoprefixer from 'gulp-autoprefixer';
import cleanCSS from 'gulp-clean-css';
import rename from 'gulp-rename';
import * as sass from 'sass';

// Initialize Sass compiler
const sassCompiler = sassModule(sass);

// Define Sass compilation task
function compileSass() {
  return gulp.src('./sass/main.scss')
    .pipe(sassCompiler().on('error', sassCompiler.logError))
    .pipe(autoprefixer())
    .pipe(cleanCSS({ compatibility: 'ie8' }))
    .pipe(rename('billingstyles.css')) // Output file name
    .pipe(gulp.dest('./')); // Output directory (root)
}

// Define default task
function defaultTask() {
  return compileSass();
}

// Define watch task to automatically compile Sass on file changes
function watch() {
  gulp.watch('./sass/**/*.scss', compileSass);
}

// Export tasks
export { compileSass, watch, defaultTask as default };
