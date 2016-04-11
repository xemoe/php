var gulp = require('gulp'),  
    sys = require('sys'),
    exec = require('child_process').exec;

gulp.task('phpunit', function() {
    exec('clear && date && ./phpunit.sh', function(error, stdout) {
        sys.puts(stdout); 
    });
}); 

gulp.task('default', function() {
    gulp.watch(['../src/**/*.php', 'tests/unit/**/*.php'], { debounceDelay: 2000 }, ['phpunit']);   
});
