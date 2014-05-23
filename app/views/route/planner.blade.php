<!DOCTYPE html>
<html lang="en" ng-app="irailapp" ng-controller="PlannerCtrl">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>iRail.be</title>
    <link rel="stylesheet" href="{{ URL::asset('bower_components/bootstrap-sass/lib/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('bower_components/fontawesome/css/font-awesome.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('css/main.css') }}">
    <script src="{{ URL::asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular/angular.min.js') }}"></script>
    <script src="{{ URL::asset('bower_components/bootstrap-sass/dist/js/bootstrap.js') }}"></script>
    <script src="{{ URL::asset('bower_components/angular-bootstrap/ui-bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/irailapp/app.js') }}"></script>
</head>
<body>
<div class="wrapper">
    <div id="main">
        <div class="navbar navbar-inverse navbar-static-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><img src="images/logo.svg" /> <strong>iRail</strong> route planner</a>
                </div>
                <div class="navbar-collapse collapse navbar-right">
                    <ul class="nav navbar-nav">
                        <li><a href="#" ng-click="reset()"><i class="fa fa-road fa-12"></i> Plan new route</a></li>
                        <li><a href="{{ URL::to('/stations') }}"><i class="fa fa-search fa-12"></i> Search stations</a></li>
                    </ul>
                </div><!--/.nav-collapse -->
            </div>
        </div>
        <div class="container">
            <div class="row routeplanner view1 well" ng-show="planning">
                <div class="col-sm-6">
                    <script type="text/ng-template" id="customTemplate.html">
                        <a>
                            <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
                        </a>
                    </script>
                    <div class="form-group">
                        <label for="departure">Departure station</label>
                        <input type="text" ng-model="departure" placeholder="Type a departure station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control input-lg">
                    </div>
                    <div class="form-group">
                        <label for="destination">Destination station</label>
                        <input type="text" ng-model="destination" placeholder="Type a destination station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control input-lg">
                    </div>
                    <label for="destination">Choose your date</label>
                    <div class="datepicker">
                        <datepicker ng-class="time" ng-model="mydate" show-weeks="false"></datepicker>
                    </div>
                    <br/>
                </div>
                <div class="col-sm-6">
                    <label for="destination">Pick a time</label>
                    <select class="form-control input-lg timepicker" ng-model="timeoption">
                        <option value="depart">Departure at chosen hour</option>
                        <option value="arrive">Arrival at chosen hour</option>
                    </select>
                    <timepicker ng-model="mytime" ng-change="changed()" show-meridian="ismeridian"></timepicker>
                    <br/>
                    <input type="submit" class="btn btn-default btn-lg btn-primary btn-wide" ng-click="save()" value="Find trains">
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
                    <h4 class="center lg">Loading your results! Sit tight.</h4>
                    <p class="small center">Your results will be here in a few seconds.</p>
                </div>
            </div>
            <div class="row max-w5" ng-show="error" >
                <div class="col-md-12 col-sm-12">
                    <div class="well">
                        <h1 class="center"><i class="fa fa-support fa-3x center"></i>
                        </h1>
                        <h3>Something seems to have gone wrong. <strong>We could not find any routes</strong>.</h3>
                        <p>This sometimes happens when data is unavailable (e.g. date far in the future). Please try again. If this problem persists, <a href="mailto:iRail@list.iRail.be">mail us</a>.</p>
                        <br/>
                        <a href="#" ng-click="reset()" class="btn btn-danger btn-lg btn-wide"><i class="fa fa-chevron-left"></i> Return to planner</a>
                        <br/>
                    </div>
                </div>
            </div>
            <div class="row" ng-show="results">
                <div class="col-md-9 col-sm-8">
                    <h4>
                        From <strong>@{{departure.name}}</strong> to <strong>@{{destination.name}}</strong>
                        <br/>
                        on <strong>@{{mydate | date}}</strong>.
                        <br/>
                        You want to <strong>@{{timeoption}}</strong> at @{{mytime | date : 'HH:mm' }}.
                    </h4>
                    <div ng-show="backintime">
                        <hr/>
                        <h4>You're trying to back in time? Good on ya.</h4>
                    </div>
                    <hr/>
                    <h5>@{{connections.length}} routes found. Tap the headers below to expand. We automatically expanded the optimal route.</h5>
                    <div class="panel-group results" id="accordion">
                        <div class="panel panel-default" ng-repeat="conn in connections">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" ng-href="#result-@{{connections.indexOf(conn)}}">
                                <span class="container33">
                                    <span class="tleft">
                                        @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                        &rarr;
                                        @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                                    </span>
                                    <span class="tcenter">
                                        <strong>
                                            <i class="fa fa-clock-o"></i> @{{ ((conn.arrival.time-conn.departure.time))/60 }} min
                                        </strong>
                                    </span>
                                    <span class="tright">
                                        @{{ conn.vias.number }}
                                    </span>
                                </span>
                                    </a>
                                </h4>
                            </div>
                            <div id="result-@{{connections.indexOf(conn)}}" class="panel-collapse collapse" ng-class="{in : $first}"  >
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <span class="badge">@{{ conn.departure.platform }}</span>
                                    <span class="planner-time"><strong>
                                            @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                        </strong>
                                    </span>
                                    <span class="planner-station">
                                        @{{ conn.departure.station}}
                                    </span>

                                    </li>
                                    <li class="list-group-item" ng-repeat="stop in conn.vias.via">
                                        &darr; @{{stop.vehicle.replace("BE.NMBS.","")}} <span class="small">(@{{stop.direction.name}})</span>
                                        <br/>
                                        <span class="badge">@{{ stop.departure.platform }}</span>
                                    <span class="planner-time"><strong>
                                            @{{ (stop.departure.time)*1000 | date:'HH:mm' }}
                                        </strong>
                                    </span>
                                    <span class="planner-station">
                                    @{{ stop.station}}
                                    </span>
                                        <br/>
                                    </li>
                                    <li class="list-group-item">
                                        &darr; @{{conn.arrival.vehicle.replace("BE.NMBS.","")}} <span class="small">(@{{conn.arrival.direction.name}})</span>
                                        <br/>
                                        <span class="badge">@{{ conn.arrival.platform }}</span>
                                    <span class="planner-time"><strong>
                                            @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                                        </strong></span>
                                    <span class="planner-station">
                                        @{{ conn.arrival.station}}
                                    </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="visible-print">
                        <br/>
                        <p>
                            This route was planned on iRail.be. Thank you very much for using our webapp.
                        </p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 hidden-print">
                    <br/>
                    <div class="btn-group btn-wide btn-botm">
                        <a class="btn btn-default btn-lg btn-50" ng-click="earlier()">&lt; Earlier</a>
                        <a class="btn btn-default btn-lg btn-50" ng-click="later()">Later &gt;</a>
                    </div>
                    <div class="btn-group btn-wide btn-botm">
                        <a class="btn btn-default btn-lg btn-50" ng-click="earliest()">&lt;&lt; Earliest</a>
                        <a class="btn btn-default btn-lg btn-50" ng-click="latest()">Latest &gt;&gt;</a>
                    </div>
                    <a class="btn btn-primary btn-wide btn-lg btn-botm" ng-click="reverse()"><i class="fa fa-exchange"></i> Reverse trip</a>
                    <a class="btn btn-default btn-wide btn-lg btn-botm" ng-click="reset()"><i class="fa fa-undo"></i> Plan another trip</a>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer container">
    <hr/>
    <p>&copy; 2014, OKFN Belgium. iRail is a part of <a href="http://okfn.be/" target="_blank">Open Knowledge Foundation Belgium</a>. </p>
</footer>
</body>
</html>