var StationSearchCtrl = function ($scope, $http, $filter, $timeout) {
    $http.get('../data/stations.json').success( function (data) {
        $scope.stations = data;
    });

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
        StationSearchCtrl
    ]);
