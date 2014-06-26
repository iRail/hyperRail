module.exports = {
	js: {
		files: {
			'<%= paths.compiled.js %>/scripts.js': [
                '<%= paths.components.jquery %>',
                '<%= paths.components.angular %>',

                '<%= components %>/angular-animate/angular-animate.min.js',
                '<%= components %>/bootstrap-sass/dist/js/bootstrap.js',
                '<%= components %>/angular-bootstrap/ui-bootstrap-tpls.min.js',

				'<%= paths.original.js %>/**/*.js',
			],
		},
	}
};