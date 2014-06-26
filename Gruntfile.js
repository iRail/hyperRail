module.exports = function (grunt) {

	// Load modules
	require('jit-grunt')(grunt, {
	});

	/**
	 * Loads all available tasks options
	 *
	 * @param {String} folder
	 *
	 * @return {Object}
	 */
	function loadConfig(folder) {
		var glob = require('glob');
		var path = require('path');
		var key;

		glob.sync('**/*.js', {cwd: folder}).forEach(function (option) {
			key = path.basename(option, '.js');
			config[key] = require(folder + option);
		});
	}

	////////////////////////////////////////////////////////////////////
	//////////////////////////// CONFIGURATION /////////////////////////
	////////////////////////////////////////////////////////////////////

	var config = {
		pkg : grunt.file.readJSON('package.json'),
		name: '<%= pkg.name %>',

		grunt     : '.grunt',
		app       : 'public/app',
		builds    : 'public/builds',
		components: 'public/bower_components',

		paths: {
			original: {
				css       : '<%= app %>/css',
				js        : '<%= app %>/js',
				sass      : '<%= app %>/sass',
			},
			compiled  : {
				css  : '<%= builds %>/css',
				js   : '<%= builds %>/js',
			},
			components: {
				jquery   : '<%= components %>/jquery/dist/jquery.js',
				angular  : '<%= components %>/angular/angular.js',
			}
		},
	};

	// Load all tasks
	var gruntPath = './' + config.grunt + '/';
	loadConfig(gruntPath);
	grunt.initConfig(config);

	// Load custom tasks
	require(gruntPath + 'tasks.js')(grunt);

};