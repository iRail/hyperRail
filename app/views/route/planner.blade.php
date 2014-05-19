<!DOCTYPE html>
<html lang="en" ng-app="irailapp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>iRail.be</title>
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap-sass/lib/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular/angular.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/bootstrap-sass/dist/js/bootstrap.js') }}"></script>
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
<div class="container" ng-controller="StationListCtrl">
    <div class="row">
        <div class="col-sm-12">
            <h1>iRail Route Planner</h1>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-md-8">
            <script type="text/ng-template" id="customTemplate.html">
                <a>
                    <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
                </a>
            </script>
            <div class="form-group">
            <label for="departure">Departure station</label>
            <input type="text" ng-model="departure" placeholder="Type a departure station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control">
            </div>
            <div class="form-group">
                <label for="destination">Destination station</label>
                <input type="text" ng-model="destination" placeholder="Select a destination station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control">
            </div>
            <hr/>
            <pre ng-show="data != null">Model: @{{data | json}}</pre>
            <div class="alert alert-danger" ng-show="data === null">
                <p ng-show="stationnotfound === true">We could not translate your text to a station. <strong>Please check your input</strong>. We automatically suggest possible stations! :)</p>
                <p ng-show="mytime === undefined">Don't forget to set the time.</p>
                <p ng-show="mydate === undefined">Don't forget to set the date.</p>
            </div>
        </div>
        <div class="col-md-4">
            <label for="destination">Choose your date</label>
            <datepicker ng-model="mydate" show-weeks="true"></datepicker>
            <hr/>
            <label for="destination">Pick a time</label>
            <select class="form-control input-lg" ng-model="timeoption">
                <option value="arrival">Arrival at chosen hour</option>
                <option value="departure">Departure at chosen hour</option>
            </select>
            <timepicker ng-model="mytime" ng-change="changed()" show-meridian="ismeridian"></timepicker>
            <hr/>
            <a class="btn btn-default btn-lg btn-primary" ng-click="save()">Plan route</a>
            <hr/>
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