(function(){

    var irailapp = angular.module('irailapp', ['ui.bootstrap']);

    irailapp.controller('StationListCtrl', function ($scope, $http, $filter, $timeout) {

        // Init departure and destination as undefined
        $scope.departure = undefined;
        $scope.destination = undefined;
        $scope.mytime = new Date();
        $scope.mydate = new Date();
        $scope.timeoption = 'arrival';

        $scope.planning = true;
        $scope.loading = false;
        $scope.results = false;

        // Fetch stations via HTTP GET request
        $http.get('data/stations.json').success(function(data) {
            $scope.stations = data;
            console.log($scope.stations);
        });

        // Check if we can dump the data
        $scope.saveDataCheck = function(){
            if('id' in $scope.departure && 'id' in $scope.destination){
                $scope.data = {
                    "departure" : $scope.departure,
                    "destination" : $scope.destination,
                    "date" : $filter('date')($scope.mydate, 'shortDate'),
                    "time" : $filter('date')($scope.mytime, 'HH:mm'),
                    "timeoption" : $scope.timeoption
                };
                $scope.planning = false;
                $scope.loading = true;
                $timeout(function(){
                    $scope.loading = false;
                    $scope.results = true;
                }, 10000);

            }
        };

        $scope.save = function(){
            // Check if time and date are set
            if ($scope.mydate === undefined){
                $scope.data = null;
                return;
            }
            if ($scope.mytime === undefined){
                $scope.data = null;
                return;
            }
            // Check if departure and destination are bound
            try{
                $scope.saveDataCheck();
            }
            // If not bound, try to bind data (1 attempt)
            catch(ex){
                    for (var i = 0, len = $scope.stations.stations.length; i < len; i++) {
                        try{
                            if (($scope.stations.stations[i].name.toLowerCase()).indexOf($scope.departure.toLowerCase()) != -1){
                                arr = $scope.stations.stations;
                                $scope.departure = arr[i];
                            }
                        }
                        catch(ex){
                        }
                        try{
                            if(($scope.stations.stations[i].name.toLowerCase()).indexOf($scope.destination.toLowerCase()) != -1){
                                arr = $scope.stations.stations;
                                $scope.destination = arr[i];
                            }
                        }
                        catch(ex){
                        }
                    }
                    try{
                        $scope.saveDataCheck();
                    }catch(ex){
                        $scope.stationnotfound = true;
                        $scope.data = null;
                    }
                }
            // Check if select box has been set
        };
    });
}());