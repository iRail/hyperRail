module.exports = {
    dist: {
        files: [
            {
                expand : true,
                src    : ['**'],
                cwd    : '<%= paths.components.bootstrap.fonts %>',
                dest   : '<%= paths.compiled.fonts %>'
            },
            {
                expand : true,
                src    : ['**'],
                cwd    : '<%= components %>/fontawesome/fonts',
                dest   : '<%= paths.compiled.fonts %>'
            },
            {
                expand : true,
                src    : ['**'],
                cwd    : '<%= paths.original.fonts %>',
                dest   : '<%= paths.compiled.fonts %>'
            },
        ]
    }
};