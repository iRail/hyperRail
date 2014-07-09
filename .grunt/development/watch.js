module.exports = {
	options: {
		livereload : true,
		interrupt  : true,
	},

	grunt: {
		files: ['Gruntfile.js', '<%= grunt %>/**/*'],
		tasks: 'default',
	},
	js: {
		files: ['<%= paths.original.js %>/**/*'],
		tasks: 'js',
	},
	css: {
		files: '<%= paths.original.sass %>/**/*.scss',
		tasks: 'css',
	},
};