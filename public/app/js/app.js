/*--------------------------------------------------------
 * SET UP AS ANGULAR APP
 *-------------------------------------------------------*/

var irailapp = angular.module('irailapp',
    [
        'ui.bootstrap',
        'ngAnimate',
        'irailapp.controllers',
    ]
);

angular.module('irailapp.controllers', []);

/**
 * Helper method to get a URL parameter
 * @param {[type]} sParam [description]
 */
function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i=i+1)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] === sParam)
        {
            return sParameterName[1];
        }
    }
}
