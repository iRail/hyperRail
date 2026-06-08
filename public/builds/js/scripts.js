/*--------------------------------------------------------
 * SET UP AS ANGULAR APP
 *-------------------------------------------------------*/

var irailapp = angular.module('irailapp',
    [
        'ui.bootstrap',
        'ngAnimate',
        'irailapp.services',
        'irailapp.controllers'
    ]
);

angular.module('irailapp.services', []);
angular.module('irailapp.controllers', []);

/**
 * Helper method to get a URL parameter
 * @param {[type]} sParam [description]
 */
function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i=i+1)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] === sParam)
        {
            return sParameterName[1];
        }
    }
}

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

var StationAutocompleteService = function ($http, $q, $timeout) {
  var htmlLang = $("html").attr("lang");
  var cache = {};
  var pendingByQuery = {};
  var byIdCache = {};
  var pendingById = {};
  var latestQueryToken = 0;
  var debouncePromise = null;
  var waitMs = 180;

  function getAlternativeName(alternative, lang) {
    if (alternative instanceof Array) {
      for (var index = 0; index < alternative.length; index = index + 1) {
        if (alternative[index]["@language"] === lang) {
          return alternative[index]["@value"];
        }
      }

      return null;
    }

    if (alternative && alternative["@language"] === lang) {
      return alternative["@value"];
    }

    return null;
  }

  function normalizeStation(station) {
    if (!station || !station.alternative) {
      return station;
    }

    var alternativeName = getAlternativeName(station.alternative, htmlLang);

    if (alternativeName) {
      station.name = alternativeName;
    }

    return station;
  }

  function normalizeStations(data) {
    var stations = (data && data["@graph"]) || [];

    for (var index = 0; index < stations.length; index = index + 1) {
      normalizeStation(stations[index]);
    }

    return stations;
  }

  function setDebounce(milliseconds) {
    if (milliseconds >= 0) {
      waitMs = milliseconds;
    }
  }

  function searchStationsNow(query) {
    var normalizedQuery = query || "";

    if (cache[normalizedQuery]) {
      return $q.when(cache[normalizedQuery]);
    }

    if (pendingByQuery[normalizedQuery]) {
      return pendingByQuery[normalizedQuery];
    }

    pendingByQuery[normalizedQuery] = $http.get("/stations/NMBS", {
      params: {
        q: normalizedQuery
      }
    }).then(function (res) {
      var stations = normalizeStations(res.data);
      cache[normalizedQuery] = stations;
      return stations;
    }).finally(function () {
      delete pendingByQuery[normalizedQuery];
    });

    return pendingByQuery[normalizedQuery];
  }

  function searchStations(query) {
    latestQueryToken = latestQueryToken + 1;
    var token = latestQueryToken;
    var deferred = $q.defer();

    if (debouncePromise) {
      $timeout.cancel(debouncePromise);
    }

    debouncePromise = $timeout(function () {
      searchStationsNow(query).then(function (stations) {
        if (token === latestQueryToken) {
          deferred.resolve(stations);
        } else {
          deferred.resolve([]);
        }
      }, function (error) {
        deferred.reject(error);
      });
    }, waitMs);

    return deferred.promise;
  }

  function getStationById(stationId) {
    if (!stationId) {
      return $q.when(null);
    }

    if (byIdCache[stationId]) {
      return $q.when(byIdCache[stationId]);
    }

    if (pendingById[stationId]) {
      return pendingById[stationId];
    }

    pendingById[stationId] = $http.get("/stations/NMBS", {
      params: {
        id: stationId
      }
    }).then(function (res) {
      var stations = normalizeStations(res.data);
      var station = stations.length > 0 ? stations[0] : null;
      byIdCache[stationId] = station;
      return station;
    }).finally(function () {
      delete pendingById[stationId];
    });

    return pendingById[stationId];
  }

  return {
    setDebounce: setDebounce,
    searchStations: searchStations,
    getStationById: getStationById
  };
};

angular.module("irailapp.services")
  .factory("StationAutocompleteService", [
    "$http",
    "$q",
    "$timeout",
    StationAutocompleteService
  ]);
