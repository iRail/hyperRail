var elixir = require('laravel-elixir');
var gulp   = require('gulp');
var sass   = require('gulp-ruby-sass');
var bower  = require('gulp-bower');
var notify = require('gulp-notify');

var config = {
    sassPath: './resources/assets/sass',
    bowerDir: './bower_components'
}

gulp.task('bower', function () {
    return bower()
        .pipe(gulp.dest(config.bowerDir))
});

gulp.task('icons', function () {
    return gulp.src(config.bowerDir + '/fontawesome/fonts/**.*')
        .pipe(gulp.dest('./public/assets/fonts'));
});

gulp.task('css', function () {
    return sass(config.sassPath + '/main.scss', {
        style: 'compressed',
        loadPath: [
            './resources/sass',
            config.bowerDir + '/bootstrap-sass-official/assets/stylesheets',
            config.bowerDir + '/fontawesome/scss',
        ]
    })
        .on("error", notify.onError(function (error) {
            return "Error: " + error.message;
        }))
        .pipe(gulp.dest('./public/assets/css'));
});
