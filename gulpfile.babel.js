'use strict';

import sass from 'gulp-sass';
import babel from 'gulp-babel';
import uglify from 'gulp-uglify';
import svgmin from 'gulp-svgmin';
import rename from 'gulp-rename';
import plumber from 'gulp-plumber';
import svgstore from 'gulp-svgstore';
import imagemin from 'gulp-imagemin';
import cleancss from 'gulp-clean-css';
//import sourcemaps from 'gulp-sourcemaps';
import autoprefixer from 'gulp-autoprefixer';
import {task, src, dest, series, parallel, watch} from 'gulp';

task('svg', () => {
    return src('assets/img/svg/*.svg')
        .pipe(plumber())
        .pipe(svgmin({
            plugins: [
                {removeViewBox: false},
                {removeUselessDefs: true},
            ],
            js2svg: {pretty: false}
        }))
        .pipe(svgstore({inlineSvg: true}))
        .pipe(rename({basename: 'sprite', extname: '.svg'}))
        .pipe(dest('assets/img'));
});

task('sass', () => {
    return src('assets/sass/**/*.scss')
        .pipe(plumber())
        // .pipe(sourcemaps.init())
        .pipe(sass({
            outputStyle: 'expanded', // nested, expanded, compact, compressed
            precision: 5,
            includePaths: ['assets/sass'],
            indentType: 'space',
            indentWidth: 2,
            linefeed: 'crlf',
            sourceComments: false,
        }).on('error', sass.logError))
        .pipe(autoprefixer({cascade: true, grid: 'autoplace'}))
        // .pipe(sourcemaps.write('/'))
        .pipe(dest('./'));
});

task('css', () => {
    return src('style.css')
        .pipe(plumber())
        .pipe(cleancss({compatibility: 'ie7', debug: true}))
        .pipe(rename({suffix: '.min'}))
        .pipe(dest('./'));
});

task('babel', () => {
    return src('assets/js/es6/**/*.js')
        .pipe(plumber())
        .pipe(babel())
        .pipe(uglify({
            mangle: false,
            compress: false,
            output: {
                beautify: true,
            },
        }))
        .pipe(dest('assets/js'));
});

task('js', () => {
    return src('assets/js/brainworks.js')
        .pipe(plumber())
        .pipe(uglify({
            mangle: false,
            compress: false,
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(dest('assets/js'));
});

task('images', () => {
    return src('assets/img/**/*')
        .pipe(imagemin({
            svgoPlugins: [{
                removeViewBox: false,
            }],
            verbose: true,
        }))
        .pipe(dest('assets/img-optimized'))
});

task('watch', () => {
    watch('assets/img/svg/*.svg', series('svg'));
    watch('assets/sass/**/*.scss', series('sass'));
    watch('assets/js/es6/**/*.js', series('babel'));
});

task('default', parallel('css', 'js'));
