var StationSearchCtrl = function ($scope, $http, $filter, $timeout, StationAutocompleteService) {

  StationAutocompleteService.setDebounce(180);
  
  $scope.getStations = function(query) {
    return StationAutocompleteService.searchStations(query);
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
    'StationAutocompleteService',
    StationSearchCtrl
  ]);
