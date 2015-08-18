module.exports = function(grunt) {

    ////////////////////////////////////////////////////////////////////
    /////////////////////////////// COMMANDS ///////////////////////////
    ////////////////////////////////////////////////////////////////////

    grunt.registerTask('default', 'Build assets for local', [
        'css',
        'js',
        'copy',
    ]);

    grunt.registerTask('production', 'Build assets for production', [
        'css',
        'js',
        'concat',
        'minify'
    ]);

    // Flow
    ////////////////////////////////////////////////////////////////////

    grunt.registerTask('minify', 'Minify assets', [
        'uglify',
    ]);

    grunt.registerTask('js', 'Build scripts', [
        'jshint',
        'concat:js',
    ]);

    // Assets
    //////////////////////////////////////////////////////////////////////

    grunt.registerTask('css', 'Build stylesheets', [
        'compass:compile',
        'autoprefixer',
        'concat:css',
    ]);

};