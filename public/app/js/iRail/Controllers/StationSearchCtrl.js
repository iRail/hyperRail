var StationSearchCtrl = function ($scope, $http, $filter, $timeout) {
  
  $scope.getStations = function(query) {
    return $http.get('', {
      params: {
        q: query
      }
    }).then(function(res){
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
