module.exports = {
    options: {
        appDir             : '<%= app %>/',
        cssDir             : '../builds/css',
        generatedImagesDir : 'img/sprite/generated',
        imagesDir          : 'img',
        outputStyle        : 'compressed',
        noLineComments     : true,
        sourcemap          : true,
        relativeAssets     : true
    },

    /**
     * Cleans the created files and rebuilds them
     */
    clean: {
        options: {
            clean: true
        }
    },

    /**
     * Compile Sass files
     */
    compile: {}
};
