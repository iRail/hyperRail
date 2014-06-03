(function(){

    /*--------------------------------------------------------
     * SET UP AS ANGULAR APP
     *-------------------------------------------------------*/

    var irailapp = angular.module('irailapp', ['ui.bootstrap', 'ngAnimate']);

    irailapp.controller('PlannerCtrl', function ($scope, $http, $filter, $timeout) {

    /*--------------------------------------------------------
     * INITIAL VARIABLES & SETUP
     *-------------------------------------------------------*/
        var automatic = GetURLParameter('auto');

        // Init departure and destination as undefined
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
                        $scope.parseResults(data);
                        console.log(data);
                        window.history.pushState("departure", "iRail.be", "?to=" + $scope.destination.id
                            + '&from=' + $scope.departure.id
                            + '&date=' + ($filter('date')($scope.mydate, 'ddMMyy'))
                            + '&time=' + ($filter('date')($scope.mytime, 'HHmm'))
                            + '&timeSel=' + $scope.timeoption
                            + '&auto=true'
                        );
                        $scope.loading = false;
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
        $scope.findStationById = function(suppliedIdentifierString){
            for (var i = 0, len = $scope.stations.stations.length; i < len; i++) {
                if (($scope.stations.stations[i].id.toLowerCase()).indexOf(suppliedIdentifierString) != -1){
                    return $scope.stations.stations[i];
                }
            }
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
        $scope.resetplanner = function(e){
            e.stopPropagation();
            e.preventDefault();
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

        // Fetch stations via HTTP GET request
        $http.get('data/stations.json').success(function(data) {
            $scope.stations = data;
            // Check if we were redirected for automatic results!
            if (automatic === 'true'){
                // Check if URL params are set
                var dep = GetURLParameter('from');
                var des = GetURLParameter('to');
                var urltime = GetURLParameter('time');
                var dateparam = GetURLParameter('date');
                var timeoption = GetURLParameter('timeSel');
                if (dep != "undefined" && des != "undefined"){
                    // Get departure and destination id from URL
                    $scope.departure = $scope.findStationById(dep);
                    $scope.destination = $scope.findStationById(des);
                    // Get date
                    var parts = /^(\d\d)(\d\d)(\d{2})$/.exec(dateparam);
                    $scope.mydate = new Date( parts[3], parts[2]-1, parts[1] );
                    // Get time
                    var dat = new Date, time = urltime.split(/^(\d\d)(\d\d)$/);
                    dat.setHours(time[1]);
                    dat.setMinutes(time[2]);
                    $scope.mytime = dat;
                    // Get time option
                    if (timeoption === "arrive"){
                        $scope.timeoption = "arrive";
                    }
                    else{
                        $scope.timeoption = "depart";
                    }
                    $scope.confirmRouteSearch();
                }
            }
            if (GetURLParameter('from') !== 'undefined'){
                try{
                    $scope.departure = $scope.findStationById(GetURLParameter('from'));
                }
                catch(ex){
                    console('Could not link departure station from URL.');
                }
            }
            if (GetURLParameter('to') !== 'undefined'){
                try{
                    $scope.destination = $scope.findStationById(GetURLParameter('to'));
                }
                catch(ex){
                    console('Could not link destination station from URL.');
                }
            }
        });

    });

    irailapp.controller('StationSearchCtrl', function($scope, $http, $filter, $timeout){

        $http.get('data/stations.json').success(function(data) {
            $scope.stations = data;
        });

        $scope.resetplanner = function(){
            // Should not do anything
        }
    });

    irailapp.controller('StationLiveboardCtrl', function($scope, $http, $filter, $timeout){

        $scope.loading = true;
        var config = {headers: {
            'Accept': 'application/json'
        }
        };
        $http.get('../data/stations.json').success(function(data) {
            $scope.stations = data;
            var location = document.URL;
            // Figure out what the station name is
            var split = location.split('/stations/');
            var id = split[1].split('?');
            var stationName = $scope.findStationById(id[0]).name;
            // Set up the request path
            // url = "http://api.irail.be/liveboard/?station=" + stationName + "&lang=nl&format=json";
            // Request the URL
            $http.get(document.URL, config)
                // If it works, show data
                .success(function(data) {
                $scope.liveboardData = data;
                $scope.results = true;
                $scope.loading = false;
                $scope.error = false;
            }
            ).error(function(){
                // Otherwise, show other stuff
                $scope.results = false;
                $scope.loading = false;
                $scope.error = true;
            });
        });
        $scope.findStationById = function(suppliedIdentifierString){
            for (var i = 0, len = $scope.stations.stations.length; i < len; i++) {
                if (($scope.stations.stations[i].id.toLowerCase()).indexOf(suppliedIdentifierString) != -1){
                    return $scope.stations.stations[i];
                }
            }
        };

        $scope.resetplanner = function(){
            // Should not do anything
        }

    });

    function GetURLParameter(sParam){
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');
        for (var i = 0; i < sURLVariables.length; i++)
        {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam)
            {
                return sParameterName[1];
            }
        }
    }

}());