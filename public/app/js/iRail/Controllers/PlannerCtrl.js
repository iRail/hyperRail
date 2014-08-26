var PlannerCtrl = function ($scope, $http, $filter, $timeout, $window) {

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

  $(document).keypress(function (e) {
    if(e.which === 13 && $scope.planning === true){
      $("#confirm").focus();
    }
  });

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
    for (var i = 0, len = $scope.stations['@graph'].length; i < len; i=i+1) {
      if (($scope.stations["@graph"][i]["@id"]).indexOf(suppliedIdentifierString) !== -1) {
        return $scope.stations["@graph"][i];
      }
    }
  };


  $scope.getStations = function(query) {
    return $http.get('/stations/NMBS', {
      params: {
        q: query
      }
    }).then(function(res){
      return res["data"]["@graph"];
    });
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
      for (var i = 0, len = $scope.stations["@graph"].length; i < len; i=i+1) {
        if (($scope.stations["@graph"][i].name.toLowerCase()).indexOf($scope.departure.toLowerCase()) !== -1) {
          arr = $scope.stations["@graph"];
          $scope.departure = arr[i];
        }
        if (($scope.stations["@graph"][i].name.toLowerCase()).indexOf($scope.destination.toLowerCase()) !== -1) {
          arr = $scope.stations["@graph"];
          $scope.destination = arr[i];
        }
      }
      $scope.confirmRouteSearch();
    }
    // TODO: Check if select box has been set
  };

  /**
   * Resets the route planner to default values
   */
  $scope.resetplanner = function (e) {
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

  // Fetch stations via HTTP GET request
  $http.get("stations/NMBS",{header:{"Accept":"application/json"}}).success(function (data) {
    $scope.stations = data;
    // Check if URL params are set
    var dep = decodeURIComponent(GetURLParameter("from"));
    var des =  decodeURIComponent(GetURLParameter("to"));
    var urltime = GetURLParameter("time");
    var dateparam = GetURLParameter("date");
    var timeoption = GetURLParameter("timeSel");
    if (dep !== "undefined" && des !== "undefined") {
      // Get departure and destination id from URL
      $scope.departure = $scope.findStationById(dep);
      $scope.destination = $scope.findStationById(des);
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
      $scope.confirmRouteSearch();
    } else if (GetURLParameter("from") !== "undefined") {
      try {
        $scope.departure = $scope.findStationById(decodeURIComponent(GetURLParameter("from")));
      }
      catch (ex) {
        //console.log("Could not link departure station from URL.");
      }
    } else if (GetURLParameter("to") !== "undefined") {
      try {
        $scope.destination = $scope.findStationById(decodeURIComponent(GetURLParameter("to")));
      }
      catch (ex) {
        //console.log("Could not link destination station from URL.");
      }
    }
  });
};

angular.module("irailapp.controllers")
    .controller("PlannerCtrl", [
        "$scope",
        "$http",
        "$filter",
        "$timeout",
        "$window",
        PlannerCtrl
    ]);
