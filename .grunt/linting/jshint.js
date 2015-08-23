module.exports = {
    options: {
        force   : true,
        browser : true,
        jquery  : true,
        devel   : true,

        bitwise       : true,
        camelcase     : true,
        curly         : true,
        eqeqeq        : true,
        freeze        : true,
        immed         : true,
        indent        : true,
        latedef       : true,
        maxcomplexity : 4,
        maxdepth      : 3,
        maxlen        : 120,
        maxparams     : 5,
        maxstatements : 15,
        newcap        : true,
        noarg         : true,
        noempty       : true,
        nonew         : true,
        plusplus      : true,
        quotmark      : true,
        trailing      : true,
        undef         : true,
        unused        : true,

        predef  : ['angular', 'Modernizr','_'],
        globals : {

        }
    },

    all: ['<%= paths.original.js %>/**/*.js', '!<%= paths.original.js %>/components/foundation.js']
};