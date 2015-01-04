var gulp            = require('gulp');
var concat          = require('gulp-concat');
var less            = require('gulp-less');
var clean           = require('gulp-clean');
var minifycss       = require('gulp-minify-css');
var autoprefixer    = require('gulp-autoprefixer');
var uglify          = require('gulp-uglify');
var sym             = require('gulp-sym');

/* dirs */
var media          = 'code/media/conferenceplus';
var mediaCssOut    = media + '/css';
var mediaJsOut     = media + '/js';
var targetBase     = '/Users/deutz/work/mappedDocRoot/jab/15';

// Clean
gulp.task('clean', function() {
    return gulp.src([mediaCssOut + '/*.css', mediaJsOut + '/*.js'], {read: false})
        .pipe(clean());
});

gulp.task('mapping', function() {
    var dof = 'administrator/components/com_conferenceplus';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true})); 

    dof = 'components/com_conferenceplus';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true})); 

    dof = 'media/conferenceplus';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true})); 

    dof = 'language/en-GB/en-GB.com_conferenceplus.ini';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true})); 

    dof = 'administrator/language/en-GB/en-GB.com_conferenceplus.ini';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true})); 

    dof = 'administrator/language/en-GB/en-GB.com_conferenceplus.sys.ini';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true}));

    dof = 'libraries/conferenceplus';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true}));

    dof = 'plugins/user/conferenceplus';
    gulp.src('code/' + dof)
        .pipe(sym(targetBase + '/' + dof, {force:true}));

    dof = 'language/en-GB/en-GB.plg_user_conferenceplus.sys.ini';
    gulp.src('code/plugins/user/conferenceplus/' + dof)
        .pipe(sym(targetBase + '/administrator/' + dof, {force:true}));

    dof = 'language/en-GB/en-GB.plg_user_conferenceplus.ini';
    gulp.src('code/plugins/user/conferenceplus/' + dof)
        .pipe(sym(targetBase + '/administrator/' + dof, {force:true}));

});

var mediaScripts1 = [
    media + '/assets/js/fileupload/vendor/jquery.ui.widget.js',
    media + '/assets/js/fileupload/jquery.iframe-transport.js',
    media + '/assets/js/fileupload/jquery.fileupload.js',
    media + '/assets/js/conferenceplus-fileupload.js'
]

gulp.task('mediaJsFileupload', function(){
    return gulp.src(mediaScripts1)
        .pipe(concat('fileupload.js'))
        .pipe(uglify())
        .pipe(gulp.dest(mediaJsOut));

});

gulp.task('mediaCss', function(){
    return gulp.src(media + '/assets/less/main.less')
        .pipe(less())
        .pipe(minifycss())
        .pipe(gulp.dest(mediaCssOut));
});


gulp.task('watchmedia',function (){
    gulp.watch(media + '/assets/less/**/*.less', ['mediaCss']);
    gulp.watch(media + '/assets/js/**/*.js', ['mediaJsFileupload']);
});

gulp.task('setdev', function (){
    gulp.start('mapping');
});

gulp.task('default', ['clean'], function (){
    gulp.start('mediaJsFileupload', 'mediaCss');
});

