var StationSearchCtrl = function ($scope, $http, $filter, $timeout) {
  
  $scope.getStations = function(query) {
    return $http.get('', {
      params: {
        q: query
      }
    }).then(function(res){
      var lang = $('html').attr("lang");
      
      for( i in res["data"]["@graph"] ){
        var station = res["data"]["@graph"][i];

        //only if there are alternatives
        if (station["alternative"]){
          //array of alternatives
          if ( station["alternative"] instanceof Array ){
            var j = 0;
            while ( j < station["alternative"].length 
              && station["alternative"][j]["@language"] != lang){
              j++;
            }

            if ( j < station["alternative"].length ){ //one found in right lang => switch
              station["name"] = station["alternative"][j]["@value"];
            }
          }else if (station["alternative"]["@language"] == lang){ //single alternative
            station["name"] = station["alternative"]["@value"];
          }
        }
      }

      return res["data"]["@graph"];
    });
  };

  $(document).keypress( function (e) {
    if (e.which === 13) {
      $('#confirm').focus();
    }
  });

  $scope.resetplanner = function () {
    // Should not do anything
  };
};

angular.module('irailapp.controllers')
  .controller('StationSearchCtrl', [
    '$scope',
    '$http',
    '$filter',
    '$timeout',
    StationSearchCtrl
  ]);
