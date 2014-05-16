<!DOCTYPE html>
<html lang="en" ng-app="irailapp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>iRail.be</title>
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular/angular.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular-bootstrap/ui-bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/irailapp/app.js') }}"></script>
</head>
<body>

<div class="navbar navbar-inverse" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ URL::to('/') }}">Home</a></li>
                <li><a href="{{ URL::to('/stations') }}">Stations</a></li>
                <li><a href="{{ URL::to('/route') }}">Route</a></li>
                <li><a href="{{ URL::to('/apitest') }}">API Test</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <h1>Route planner</h1>
        </div>
    </div>
    <hr/>
    <div class="row" ng-hide="results" ng-controller="StationListCtrl">
        <div class="col-md-6">
            <script type="text/ng-template" id="customTemplate.html">
                <a>
                    <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
                </a>
            </script>
            <h2>Departure station</h2>
            <pre>Model: @{{departure | json}}</pre>
            <input type="text" ng-model="departure" placeholder="Type a departure station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control">
        </div>
        <div class="col-md-6">
            <h2>Destination station</h2>
            <pre>Model: @{{destination | json}}</pre>
            <input type="text" ng-model="destination" placeholder="Select a destination station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control">
        </div>
    </div>
    <div class="row" ng-show="results" ng-controller="ResultsListCtrl">

    </div>
    <hr/>
    <div class="row">
        <div class="col-sm-12">
            <a href="#" class="btn btn-default btn-lg">Plan route</a>
        </div>
    </div>
    <hr/>
    <div class="row">
        <div class="col-sm-12">
            <a href="/about">Find out more about iRail.</a>
        </div>
    </div>
</div>
</body>
</html>