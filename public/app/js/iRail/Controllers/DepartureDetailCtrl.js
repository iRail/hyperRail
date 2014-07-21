var DepartureDetailCtrl = function ($scope, $http, $filter, $timeout) {

  
  /**
   * Save the entered data and request
   */
  $scope.checkin = function (e, departure) {
    
        $( "#checkin-button").html('<img src="/images/loader.gif" alt="Loader" class="center">');

        var request = $http({
            method: "post",
            headers: {'content-type': 'application/json'},
            url: "https://irail.dev/checkins",
            data: {
                departure: departure
            }
        }).success(function(response){
              window.location.reload(false); 

        }).error(function(error){

        });

        

  };
$scope.checkout = function (e, departure) {
      //departure = encodeURIComponent(departure);

      $( "#checkin-button").html('<img src="/images/loader.gif" alt="Loader" class="center">');

      var request = $http({
            method: "delete",
            headers: {'content-type': 'application/json'},
            url: "https://irail.dev/checkins/" + departure
           
        }).success(function(response){

        }).error(function(error){

        });

        window.location.reload(false); 


  };

};

angular.module("irailapp.controllers")
    .controller("DepartureDetailCtrl", [
        "$scope",
        "$http",
        "$filter",
        "$timeout",
        DepartureDetailCtrl
    ]);
