var gulp = require("gulp");
var shell = require('gulp-shell');
var elixir = require("laravel-elixir");

elixir.extend("stylistPublish", function() {
    gulp.task("stylistPublish", function() {
        gulp.src("").pipe(shell("php ../../artisan stylist:publish"));
    });
    return this.queueTask("stylistPublish");
});
