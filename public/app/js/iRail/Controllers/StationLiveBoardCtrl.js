var StationLiveBoardCtrl = function ($scope, $http, $filter, $timeout) {
  $scope.loading = true;

  var config = {headers: {'Accept': 'application/ld+json'}};

  $http.get('', config).success( function (data) {
    $scope.stations = data;
    var location = document.URL;
    // Request the URL
    $http.get(document.URL, config)
      .success( function (data) {
        $scope.liveboardData = data;
        $scope.results = true;
        $scope.loading = false;
        $scope.error = false;
      }).error( function (ex) {
        $scope.results = false;
        $scope.loading = false;
        $scope.error = true;
      });
  });
  $scope.findStationById = function(suppliedIdentifierString){
    for (var i = 0, len = $scope.stations.stations.length; i < len; i=i+1) {
      if (($scope.stations.stations[i].id.toLowerCase()).indexOf(suppliedIdentifierString) !== -1) {
        return $scope.stations.stations[i];
      }
    }
  };

  $scope.resetplanner = function () {
    // Should not do anything
  };

	/**
	 * Helper method to determine if a result should be highlighted or not
	 *
 	 * @param index
	 * @returns {boolean}
	 */
  	$scope.isSelected = function (index) {
		return (index + 1 ) == GetURLParameter('result');
  	}

};

angular.module('irailapp.controllers')
  .controller('StationLiveboardCtrl', [
    '$scope',
    '$http',
    '$filter',
    '$timeout',
    StationLiveBoardCtrl
  ]);
