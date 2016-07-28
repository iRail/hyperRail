module.exports = {
    js: {
        files: {
            '<%= paths.compiled.js %>/scripts.js': [
                '<%= paths.components.jquery %>',
                '<%= paths.components.angular %>',

                '<%= components %>/bootstrap-sass-official/assets/javascripts/bootstrap/collapse.js',
                '<%= components %>/bootstrap-sass-official/assets/javascripts/bootstrap.min.js',

                '<%= components %>/angular-animate/angular-animate.min.js',
                '<%= components %>/angular-bootstrap/ui-bootstrap-tpls.min.js',

                '<%= paths.original.js %>/**/*.js'
            ]
        }
    }
};
