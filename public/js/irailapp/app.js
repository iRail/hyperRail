(function(){

    /*--------------------------------------------------------
     * SET UP AS ANGULAR APP
     *-------------------------------------------------------*/

    var irailapp = angular.module('irailapp', ['ui.bootstrap']);

    irailapp.controller('PlannerCtrl', function ($scope, $http, $filter, $timeout) {

    /*--------------------------------------------------------
     * INITIAL VARIABLES & SETUP
     *-------------------------------------------------------*/

        // Init departure and destination as undefined
        // TODO: unless &from=008821006&to=008892007 are set!
        $scope.departure = undefined;
        $scope.destination = undefined;

        // Time and date are automatically set
        $scope.mytime = new Date();
        $scope.mydate = new Date();
        // Timeoption defaults to arrive at set hour
        $scope.timeoption = 'depart';

        // Default states
        // TODO: unless &auto is set (which automatically searches)
        $scope.planning = true; // When planning is set to true, you can enter stations and set time
        $scope.loading = false; // When loading is set to true, you see a spinner
        $scope.results = false; // When results is set to true, results are displayed

        // Fetch stations via HTTP GET request
        $http.get('data/stations.json').success(function(data) {
            $scope.stations = data;
        });

        /*--------------------------------------------------------
         * FUNCTIONS THAT CAN BE CALLED
         *-------------------------------------------------------*/

        /**
         *  Check if we can dump the data
          */
        $scope.confirmRouteSearch = function(){
            if('id' in $scope.departure && 'id' in $scope.destination){
                $scope.data = {
                    "departure" : $scope.departure,
                    "destination" : $scope.destination,
                    "date" : $filter('date')($scope.mydate, 'shortDate'),
                    "time" : $filter('date')($scope.mytime, 'HH:mm'),
                    "timeoption" : $scope.timeoption
                };
                // Set app as loading
                $scope.results = false;
                $scope.planning = false;
                $scope.loading = true;

                // Send a request to the old iRail api
                // TODO: ensure that this request goes over HTTPs! This has to be fixed at launch!

                var url = 'http://api.irail.be/connections/?to=' + $scope.destination.name
                    + '&from=' + $scope.departure.name
                    + '&date=' + ($filter('date')($scope.mydate, 'ddMMyy'))
                    + '&time=' + ($filter('date')($scope.mytime, 'HHmm'))
                    + '&timeSel=' + $scope.timeoption
                    + '&lang=NL&format=json';

                $http.get(url)
                    .success(function(data) {
                        // console.log(data);
                        $scope.parseResults(data);
                        // The app is no longer loading content
                        $scope.loading = false;
                        // Show results
                        $scope.results = true;
                    })
                    .error(function(){
                        $scope.error = true;
                        $scope.loading = false;
                        $scope.results = false;
                        $scope.planning = false;
                    });
            }
        };

        /**
         * Used to interpret the new station id
         * @param string
         */
        $scope.findStationById = function(string){
            // TODO: return object from $scope.stations to bind
        };

        /**
         * Parse the results
         * @param data
         */
        $scope.parseResults = function(data){
            if ($scope.timeoption === "arrive"){
                // Reverse connections
                // First result should be close to your set time
                data.connection.reverse();
            }
            $scope.connections = data.connection;

        };

        /**
         * Save the entered data and request
         */
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
                $scope.confirmRouteSearch();
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
                        $scope.confirmRouteSearch();
                    }catch(ex){
                        $scope.stationnotfound = true;
                        $scope.data = null;
                    }
                }
            // TODO: Check if select box has been set
        };

        /**
         * Resets the route planner to default values
         */
        $scope.reset = function(){
            $scope.error = false;
            $scope.loading = false;
            $scope.results = false;
            $scope.planning = true;
        };

        $scope.reverse = function(){
            var destination = $scope.destination;
            $scope.destination = $scope.departure;
            $scope.departure = destination;
            $scope.confirmRouteSearch();
        };

        $scope.earlier = function(){
            var itime = $scope.mytime;
            $scope.mytime = new Date(itime.setHours(itime.getHours()-1));
            $scope.confirmRouteSearch();
        };

        $scope.later = function(){
            var itime = $scope.mytime;
            $scope.mytime = new Date(itime.setHours(itime.getHours()+1));
            $scope.confirmRouteSearch();
        };

        $scope.earliest = function(){
            var itime = $scope.mytime;
            $scope.timeoption = "depart";
            $scope.mytime = new Date(itime.setHours(0, 0, 0));
            $scope.confirmRouteSearch();
        };

        $scope.latest = function(){
            var itime = $scope.mytime;
            $scope.timeoption = "arrive";
            $scope.mytime = new Date(itime.setHours(23, 59, 0));
            $scope.confirmRouteSearch();
        };


    });

}());