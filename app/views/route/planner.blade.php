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
            <a class="navbar-brand" href="#"><strong>iRail</strong> route planner</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ URL::to('/') }}">Home</a></li>
                <li><a href="{{ URL::to('/stations') }}">Stations</a></li>
                <li><a href="{{ URL::to('/route') }}">Route</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>
<div class="container" ng-controller="StationListCtrl">
    <div class="row routeplanner view1" ng-show="planning">
        <div class="col-sm-6">
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
            <label for="destination">Choose your date</label>
            <datepicker ng-model="mydate" show-weeks="true"></datepicker>
            <br/>
        </div>
        <div class="col-sm-6">
            <label for="destination">Pick a time</label>
            <select class="form-control input-lg timepicker" ng-model="timeoption">
                <option value="arrival">Arrival at chosen hour</option>
                <option value="departure">Departure at chosen hour</option>
            </select>
            <timepicker ng-model="mytime" ng-change="changed()" show-meridian="ismeridian"></timepicker>
            <hr/>
            <a class="btn btn-default btn-lg btn-primary" ng-click="save()">Plan route</a>
            <hr/>
            <pre ng-show="data != null">Model: @{{data | json}}</pre>
            <div class="alert alert-danger" ng-show="data === null">
                <p ng-show="stationnotfound === true">We could not translate your text to a station. <strong>Please check your input</strong>. We automatically suggest possible stations! :)</p>
                <p ng-show="mytime === undefined">Don't forget to set the time.</p>
                <p ng-show="mydate === undefined">Don't forget to set the date.</p>
            </div>
        </div>
    </div>
    <div class="row" ng-show="loading">
        <div class="col-md-12 col-sm-12">
            <div class="loader">Loading...</div>
            <p class="center">Loading your results! Sit tight.</p>
            <p class="small center">Your results will be here in a few seconds.</p>
        </div>
    </div>
    <div class="row" ng-show="results">
        <div class="col-md-9 col-sm-8">
            <h4>
                From <strong>@{{departure.name}}</strong> to <strong>@{{destination.name}}</strong>
                <br/>
                on <strong>@{{mydate | date}}</strong>.
            </h4>
            <hr/>
            <h5>@{{connections.length}} routes found. Tap the headers below to expand. We automatically expanded the optimal route.</h5>
            <div class="panel-group results" id="accordion">
                <div class="panel panel-default" ng-repeat="conn in connections">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" ng-href="#result-@{{connections.indexOf(conn)}}">
                                <span class="container33">
                                    <span class="el33 tleft">
                                        @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                        &rarr;
                                        @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                                    </span>
                                    <span class="el33 tcenter">
                                        <strong>
                                            @{{ ((conn.arrival.time-conn.departure.time))/60 }} min
                                        </strong>
                                    </span>
                                    <span class="el33 tright">
                                        @{{ conn.vias.number }}
                                    </span>
                                </span>
                            </a>
                        </h4>
                    </div>
                    <div id="result-@{{connections.indexOf(conn)}}" class="panel-collapse collapse">
                        <div class="panel-body">
                            <p>
                                <strong>@{{ conn.arrival.vehicle }}</strong>
                                <span class="floatright">Destination: @{{ conn.arrival.direction.name }}</span>
                            </p>
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span class="badge">@{{ conn.departure.platform }}</span>
                                    <strong>
                                        @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                    </strong> @{{ conn.departure.station}}
                                </li>
                                <li class="list-group-item" ng-repeat="stop in conn.vias.via">
                                    &darr; @{{stop.vehicle.replace("BE.NMBS.","")}} <span class="small">(@{{stop.direction.name}})</span>
                                    <br/>
                                    <span class="badge">@{{ stop.departure.platform }}</span>
                                    <strong>
                                        @{{ (stop.departure.time)*1000 | date:'HH:mm' }}
                                    </strong>@{{ stop.station}}
                                    <br/>
                                </li>
                                <li class="list-group-item">
                                    &darr; @{{conn.arrival.vehicle.replace("BE.NMBS.","")}} <span class="small">(@{{conn.arrival.direction.name}})</span>
                                    <br/>
                                    <span class="badge">@{{ conn.arrival.platform }}</span>
                                    <strong>
                                        @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                                    </strong> &rarr; @{{ conn.arrival.station}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4">
            <a class="btn btn-default">&lt; Earlier</a>
            <a class="btn btn-default">Later &gt;</a>
            <br/>
            <br/>
            <a class="btn btn-default">&lt;&lt; Earliest</a>
            <a class="btn btn-default">Latest &gt;&gt;</a>
            <br/>
            <br/>
            <a class="btn btn-primary">Reverse trip</a>
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