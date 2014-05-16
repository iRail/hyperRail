(function(){

    var irailapp = angular.module('irailapp', ['ui.bootstrap']);

    irailapp.controller('StationListCtrl', function ($scope, $http, $filter) {

        $http.get('data/stations.json').success(function(data) {
            $scope.stations = data;
            console.log($scope.stations);
        });

        $scope.departure = undefined;
        $scope.destination = undefined;

        $scope.save = function(){
            $scope.data = {
                "departure" : $scope.departure,
                "destination" : $scope.destination,
                "date" : $filter('date')($scope.mydate, 'shortDate'),
                "time" : $filter('date')($scope.mytime, 'HH:mm'),
                "timeoption" : $scope.timeoption
            }
        }

    });

}());