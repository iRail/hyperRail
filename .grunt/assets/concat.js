module.exports = {
     css: {
        files: {
            '<%= paths.compiled.css %>/main.css': [
                '<%= components %>/fontawesome/css/font-awesome.min.css',
                '<%= components %>/animate.css/animate.min.css',

                '<%= paths.original.css %>/**/*.css',
            ],
        },
    },
	js: {
		files: {
			'<%= paths.compiled.js %>/scripts.js': [
                '<%= paths.components.jquery %>',
                '<%= paths.components.angular %>',

                '<%= components %>/bootstrap-sass/dist/js/bootstrap.min.js',

                '<%= components %>/angular-animate/angular-animate.min.js',
                '<%= components %>/angular-bootstrap/ui-bootstrap-tpls.min.js',


				'<%= paths.original.js %>/**/*.js',
			],
		},
	}
};