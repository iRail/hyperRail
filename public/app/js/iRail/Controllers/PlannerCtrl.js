/*global GetURLParameter */
var PlannerCtrl = function ($scope, $http, $filter, $timeout, $window, StationAutocompleteService) {

  StationAutocompleteService.setDebounce(180);

  function getQueryParameter(name) {
    return GetURLParameter(name);
  }

  /*--------------------------------------------------------
   * INITIAL VARIABLES & SETUP
   *-------------------------------------------------------*/

  // Init departure and destination as undefined
  $scope.departure = undefined;
  $scope.destination = undefined;
  // Time and date are automatically set
  $scope.mytime = new Date();
  $scope.mydate = new Date();
  // Timeoption defaults to arrive at set hour
  $scope.timeoption = "depart";
  // Default states
  $scope.planning = true; // When planning is set to true, you can enter stations and set time
  $scope.loading = false; // When loading is set to true, you see a spinner
  $scope.results = false; // When results is set to true, results are displayed

  /*--------------------------------------------------------
   * FUNCTIONS THAT CAN BE CALLED
   *-------------------------------------------------------*/

  /**
   *  Check if we can dump the data
   */
  /*jshint quotmark: double */
  $scope.confirmRouteSearch = function () {
    if ($scope.departure && $scope.destination && "@id" in $scope.departure && "@id" in $scope.destination) {
      $scope.data = {
        "departure" : decodeURIComponent($scope.departure),
        "destination" : decodeURIComponent($scope.destination),
        "date" : $filter("date")($scope.mydate, "shortDate"),
        "time" : $filter("date")($scope.mytime, "HH:mm"),
        "timeoption" : $scope.timeoption
      };
      // Set app as loading
      $scope.results = false;
      $scope.planning = false;
      $scope.loading = true;

      window.history.pushState("departure", "iRail.be", "?to=" + encodeURIComponent($scope.destination["@id"])
                               + "&from=" + encodeURIComponent($scope.departure["@id"])
                               + "&date=" + ($filter("date")($scope.mydate, "ddMMyy"))
                               + "&time=" + ($filter("date")($scope.mytime, "HHmm"))
                               + "&timeSel=" + $scope.timeoption
                              );

      var $urlInWindow = document.URL;

      $http.get($urlInWindow)
        .success(function (data) {
          $scope.parseResults(data);

          $scope.loading = false;
          $scope.results = true;
        })
        .error(function () {
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
  $scope.findStationById = function (suppliedIdentifierString) {
    return StationAutocompleteService.getStationById(suppliedIdentifierString);
  };


  $scope.getStations = function(query) {
    return StationAutocompleteService.searchStations(query);
  };

  /**
   * Parse the results
   * @param data
   */
  $scope.parseResults = function (data) {
    if ($scope.timeoption === "arrive") {
      // Reverse connections
      // First result should be close to your set time
      data.connection.reverse();
    }

    $scope.connections = data.connection;

  };

  /**
   * Save the entered data and request
   */
  $scope.save = function () {
    // Check if time and date are set
    if ($scope.mydate === undefined) {
      $scope.data = null;
      return;
    }
    if ($scope.mytime === undefined) {
      $scope.data = null;
      return;
    }
    // Check if departure and destination are bound


    if ($scope.departure && $scope.destination) {
      $scope.confirmRouteSearch();
      // If not bound, try to bind data
    } else {
      $scope.confirmRouteSearch();
    }
    // TODO: Check if select box has been set
  };

  /**
   * Resets the route planner to default values
   */
  $scope.resetplanner = function () {
    $window.location.reload();
  };

  $scope.reverse = function () {
    var destination = $scope.destination;
    $scope.destination = $scope.departure;
    $scope.departure = destination;
    $scope.confirmRouteSearch();
  };

  $scope.earlier = function () {
    var itime = $scope.mytime;
    $scope.mytime = new Date(itime.setHours(itime.getHours()-1));
    $scope.confirmRouteSearch();
  };

  $scope.later = function () {
    var itime = $scope.mytime;
    $scope.mytime = new Date(itime.setHours(itime.getHours()+1));
    $scope.confirmRouteSearch();
  };

  $scope.earliest = function () {
    var itime = $scope.mytime;
    $scope.timeoption = "depart";
    $scope.mytime = new Date(itime.setHours(0, 0, 0));
    $scope.confirmRouteSearch();
  };

  $scope.latest = function () {
    var itime = $scope.mytime;
    $scope.timeoption = "arrive";
    $scope.mytime = new Date(itime.setHours(23, 59, 0));
    $scope.confirmRouteSearch();
  };

  // Check if URL params are set
  var dep = decodeURIComponent(getQueryParameter("from"));
  var des =  decodeURIComponent(getQueryParameter("to"));
  var urltime = getQueryParameter("time");
  var dateparam = getQueryParameter("date");
  var timeoption = getQueryParameter("timeSel");
  if (dep !== "undefined" && des !== "undefined") {
    // Get date
    var parts = /^(\d\d)(\d\d)(\d{2})$/.exec(dateparam);
    $scope.mydate = new Date( 20 + parts[3], parts[2]-1, parts[1] );
    // Get time
    var dat = new Date(), time = urltime.split(/^(\d\d)(\d\d)$/);
    dat.setHours(time[1]);
    dat.setMinutes(time[2]);
    $scope.mytime = dat;
    // Get time option
    if (timeoption === "arrive") {
      $scope.timeoption = "arrive";
    }
    else {
      $scope.timeoption = "depart";
    }

    StationAutocompleteService.getStationById(dep).then(function (station) {
      $scope.departure = station;

      return StationAutocompleteService.getStationById(des);
    }).then(function (station) {
      $scope.destination = station;
      $scope.confirmRouteSearch();
    });
  } else if (getQueryParameter("from") !== "undefined") {
    StationAutocompleteService.getStationById(decodeURIComponent(getQueryParameter("from"))).then(function (station) {
      $scope.departure = station;
    });
  } else if (getQueryParameter("to") !== "undefined") {
    StationAutocompleteService.getStationById(decodeURIComponent(getQueryParameter("to"))).then(function (station) {
      $scope.destination = station;
    });
  }
  $scope.getHours = function(minutes){
    var hours = Math.floor(minutes/60),
        str = "";
    str = hours;
    return str;
  };
  $scope.getMinutes = function(minutes){
    var minutes = Math.floor(minutes%60),
        str = "";
    str = minutes;
    return str;
  };


  $scope.selectOccupancy = function($event)
  {
    $event.preventDefault();

    var feedbackUrl = 'https://api.irail.be/feedback/occupancy';
    var $selectedOccupancyElement = $($event.currentTarget);
    var connection = $selectedOccupancyElement.data('connection');
    var occupancy = $selectedOccupancyElement.data('occupancy');

    var vehicle = $selectedOccupancyElement.data('vehicle');

    var from = $selectedOccupancyElement.data('from').split('/')[5];
    var to = $selectedOccupancyElement.data('to');
    var departureTime = $selectedOccupancyElement.data('date');

    var departureTime = new Date(departureTime * 1000).toISOString().substr(0,10).replace(/-/g,'');

    var data = {
      "connection": connection,
      "occupancy" : "http://api.irail.be/terms/"+occupancy,
      "vehicle"   : "http://irail.be/vehicle/" + vehicle.split('.')[2],
      "from"      : "http://irail.be/stations/NMBS/00" + from,
      "to"        : to,
      "date"      : departureTime
    }

    var $dropdownElement = $selectedOccupancyElement.parent().parent().parent().find('button').first();

    // Put selected occupancy in dropdown label
    $dropdownElement.html($selectedOccupancyElement.html() + '<span class="caret"></span>');

    $http.post(feedbackUrl, data, {header:{"Content-Type":"application/json"}}).then(function( response ){
        if(response.status == 201)
        {
          // Remove feedback form + show thank you message!
          var $feedbackForm = $dropdownElement.parent().html('Thank you for your feedback!');
        }
    }, function(errorResponse){
        // Remove feedback form + show error message
        var $feedbackForm = $dropdownElement.parent().html('Something went wrong. Try again.');
    });
  };
};

angular.module("irailapp.controllers")
    .controller("PlannerCtrl", [
        "$scope",
        "$http",
        "$filter",
        "$timeout",
        "$window",
        "StationAutocompleteService",
        PlannerCtrl
    ]);
