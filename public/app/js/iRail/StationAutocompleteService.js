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
