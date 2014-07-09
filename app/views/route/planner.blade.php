@extends('layouts.default')
@section('content')
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row routeplanner view1" ng-show="planning">
                <div class="col-sm-6">
                    <script type="text/ng-template" id="customTemplate.html">
                        <a>
                            <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
                        </a>
                    </script>
                    <div class="form-group">
                        <label for="departure">{{Lang::get('client.fromStation')}}</label>
                        <div class="input-group">
                        <input type="text" ng-model="departure" placeholder="{{Lang::get('client.typeFromStation')}}" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control input-lg" tabindex="1">
                        <a class="input-group-addon" ng-show="departure.id" href="stations/NMBS/@{{departure.id}}" target="_blank" data-toggle="tooltip" data-placement="left" title="{{Lang::get('client.viewLiveboard')}}"><i class="fa fa-clock-o"></i></a>
                        <span class="input-group-addon" ng-hide="departure.id"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="destination">{{Lang::get('client.toStation')}}</label>
                        <div class="input-group">
                            <input type="text" ng-model="destination" placeholder="{{Lang::get('client.typeToStation')}}" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" typeahead-on-select='focusOnConfirm()' class="form-control input-lg" tabindex="2">
                            <a class="input-group-addon btn" ng-show="destination.id" href="stations/NMBS/@{{destination.id}}" target="_blank" data-toggle="tooltip" data-placement="left" title="{{Lang::get('client.viewLiveboard')}}"><i class="fa fa-clock-o"></i></a>
                            <span class="input-group-addon" ng-hide="destination.id"></span>
                        </div>
                    </div>
                    <label for="destination">{{Lang::get('client.chooseDate')}}</label>
                    <div class="datepicker">
                        <datepicker ng-class="time" ng-model="mydate" show-weeks="false" ></datepicker>
                    </div>
                    <br/>
                </div>
                <div class="col-sm-6">
                    <label for="destination">{{Lang::get('client.chooseTime')}}</label>
                    <select class="form-control input-lg timepicker" ng-model="timeoption">
                        <option value="depart">{{Lang::get('client.departureAtHour')}}</option>
                        <option value="arrive">{{Lang::get('client.arrivalAtHour')}}</option>
                    </select>
                    <timepicker ng-model="mytime" ng-change="changed()" minute-step="15" show-meridian="ismeridian"></timepicker>
                    <br/>
                    <input type="submit" id="confirm" class="btn btn-default btn-lg btn-primary btn-wide" ng-click="save()" value="{{Lang::get('client.confirmSearch')}}" ng-hide="departure == destination">
                    <div class="alert alert-danger" ng-show="data === null">
                        <p ng-show="stationnotfound === true">{{Lang::get('client.errorCheckInput')}}</p>
                    </div>
                    <div class="alert alert-info" ng-show="departure == destination && departure != null && destination != null">
                       <p>{{Lang::get('client.stationsIdentical')}}</p>
                    </div>
                </div>
            </div>
            <div class="row loading" ng-show="loading">
                <div class="col-md-12 col-sm-12">
                    <div class="loader">Loading...</div>
                    <h4 class="center lg">{{Lang::get('client.loadingHeader')}}</h4>
                    <p class="small center">{{Lang::get('client.loadingSub')}}</p>
                </div>
            </div>
            <div class="row max-w5" ng-show="error" >
                <div class="col-md-12 col-sm-12">
                    <div class="well">
                        <h1 class="center"><i class="fa fa-support fa-3x center"></i>
                        </h1>
                        <h3>{{Lang::get('client.error')}} <strong>{{Lang::get('client.errorNoRoutes')}}</strong></h3>
                        <p>{{Lang::get('client.errorExplanation')}} <a href="mailto:iRail@list.iRail.be">{{Lang::get('client.errorMail')}}</a>.</p>
                        <br/>
                        <a href="#" ng-click="resetplanner($event)" class="btn btn-danger btn-lg btn-wide"><i class="fa fa-chevron-left"></i> {{Lang::get('client.errorReturn')}}</a>
                        <br/>
                    </div>
                </div>
            </div>
            <div class="row" ng-show="results">
                <div class="col-md-9 col-sm-8">
                    <h4>
                        {{Lang::get('client.from')}} <strong>@{{departure.name}}</strong> {{Lang::get('client.to')}} <strong>@{{destination.name}}</strong>
                        <br/>
                        {{Lang::get('client.on')}} <strong>@{{mydate | date}}</strong>.
                        <br/>
                        {{Lang::get('client.youWantTo')}}
                        <span ng-show="timeoption=='depart'"><strong>{{Lang::get('client.depart')}} </strong></span>
                        <span ng-show="timeoption=='arrive'"><strong>{{Lang::get('client.arrive')}} </strong></span>
                        {{Lang::get('client.at')}} @{{mytime | date : 'HH:mm' }}.
                    </h4>
                    <hr/>
                    <h5>@{{connections.length}} {{Lang::get('client.routesFoundDescription')}}</h5>
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
                                            <i class="fa fa-clock-o"></i> @{{ ((conn.arrival.time-conn.departure.time))/60 }}'
                                        </strong>
                                    </span>
                                    <span class="tright">
                                        <span class="delay-route" ng-if="conn.departure.delay > 0">
                                            <i class="fa fa-exclamation-triangle"></i>
                                        </span>
                                        <span ng-if="conn.vias.number > 0">
                                            @{{ conn.vias.number }}x <img src="{{ URL::asset('images/stair.svg') }}" />
                                        </span>
                                    </span>
                                </span>
                                    </a>
                                </h4>
                            </div>
                            <div id="result-@{{connections.indexOf(conn)}}" class="panel-collapse collapse" ng-class="{in : $first}"  >
                                <ul class="list-group">
                                    <li class="list-group-item">
                                        <span class="badge">@{{ conn.departure.platform }}</span>
                                    <span class="planner-time">
                                        <strong>
                                            @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                        </strong>
                                    </span>
                                    <span class="planner-station">
                                        <strong>@{{ conn.departure.station}}</strong>
                                    </span>
                                    <span class="delay-route" ng-if="conn.departure.delay > 0">
                                            <i class="fa fa-exclamation-triangle"></i> + @{{ (conn.departure.delay)/60 }}'
                                    </span>
                                    </li>
                                    <li class="list-group-item" ng-repeat="stop in conn.vias.via">
                                        &darr; @{{stop.vehicle.replace("BE.NMBS.","")}} <span class="small">(@{{stop.direction.name}})</span>
                                        <br/>
                                        <span class="planner-time">
                                            <strong>@{{(stop.arrival.time)*1000 | date:'HH:mm'}}</strong>
                                        </span>
                                        <br/>
                                        &rarr; <span class="small">@{{(stop.timeBetween/60)}} {{Lang::get('client.mins')}}</span>

                                        <br/>
                                        <span class="badge">@{{ stop.departure.platform }}</span>
                                    <span class="planner-time"><strong>
                                            @{{ (stop.departure.time)*1000 | date:'HH:mm' }}
                                        </strong>
                                    <span class="delay-route" ng-if="stop.departure.delay > 0">
                                            <i class="fa fa-exclamation-triangle"></i> + @{{ (stop.departure.delay)/60 }}'
                                    </span>
                                    </span>
                                    <span class="planner-station">
                                    <strong>@{{ stop.station}}</strong>
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
                                        <strong>@{{ conn.arrival.station}}</strong>
                                    </span>
                                    </li>

                                    <li class="list-group-item" style="text-align: right;">
                                        <a href="/favorite/" class="btn btn-info">check in</a>
                                    </li>
                                    @{{ conn }}

                                    

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
                        <a class="btn btn-default btn-50" ng-click="earlier()">&lt; {{Lang::get('client.rideEarlier')}}</a>
                        <a class="btn btn-default btn-50" ng-click="later()">{{Lang::get('client.rideLater')}} &gt;</a>
                    </div>
                    <div class="btn-group btn-wide btn-botm">
                        <a class="btn btn-default btn-50" ng-click="earliest()">&lt;&lt; {{Lang::get('client.earliestRide')}}</a>
                        <a class="btn btn-default btn-50" ng-click="latest()">{{Lang::get('client.latestRide')}} &gt;&gt;</a>
                    </div>
                    <a class="btn btn-primary btn-wide btn-lg btn-botm" ng-click="reverse()"><i class="fa fa-exchange"></i> {{Lang::get('client.reverse')}}</a>
                    <a class="btn btn-default btn-wide btn-lg btn-botm" ng-click="resetplanner($event)"><i class="fa fa-undo"></i> {{Lang::get('client.planAnother')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
    @include('core.footer')
<script>
    $("[data-toggle='tooltip']").tooltip();
</script>
</body>
</html>

@stop
