module.exports = {
	js: {
		files: {
			'<%= paths.compiled.js %>/scripts.js': [
				'<%= paths.original.js %>/**/*.js',
			],
		},
	}
};