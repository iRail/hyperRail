<div class="row" ng-show="results && connections.length > 0">
    <div class="col-md-9 col-sm-8">
        <hr/>
        <div class="panel-group results" id="accordion">
            <div class="panel panel-default" ng-repeat="conn in connections">

                <div class="panel-heading">
                    <h2 class="panel-title">
                        <a class="clearfix" data-toggle="collapse" ng-href="#result-@{{connections.indexOf(conn)}}">
                            <span class="pull-left">
                                <span class="delay-route" ng-if="conn.departure.delay > 0 || conn.departure.canceled == 1 || conn.arrival.canceled == 1">
                                    <i class="fa fa-exclamation-triangle"></i>
                                </span>
                                @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                <i class="fa fa-angle-right"></i>
                                @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                                <span class="planner-meta text-muted">
                                    <span ng-show="@{{ getHours( (conn.arrival.time-conn.departure.time)/60 ) }} > 0">
                                        @{{ getHours( (conn.arrival.time-conn.departure.time)/60 ) }}
                                        {{Lang::get('client.hoursShort')}}
                                    </span>
                                    <span ng-show="@{{ getMinutes( (conn.arrival.time-conn.departure.time)/60 ) }} > 0">
                                        @{{ getMinutes( (conn.arrival.time-conn.departure.time)/60 ) }}
                                        {{Lang::get('client.minutesShort')}}
                                    </span>
                                    <span class="planner-change" ng-if="conn.vias.number > 0">
                                        – @{{ conn.vias.number }} {{Lang::get('client.transfers')}}
                                    </span>
                                </span>
                            </span>

                            <span class="pull-right">
                                <img ng-src="/images/occupancy-@{{conn.departure.occupancy.name || 'unknown'}}.svg" alt="@{{conn.departure.occupancy.name || 'occupancy unkown'}}" height="16" width="16" />
                                <span class="badge">@{{ conn.departure.platform }}</span>
                            </span>
                        </a>
                    </h2>
                </div>

                <div id="result-@{{connections.indexOf(conn)}}" class="panel-body panel-collapse collapse" ng-class="{in : $first}"  >

                    <div class="planner-row">
                        <span class="planner-time">
                            <b>@{{ (conn.departure.time)*1000 | date:'HH:mm' }}</b>
                        </span>

                        <span class="planner-station">
                            <b>@{{ conn.departure.station}}</b>
                            <span class="delay-route" ng-if="conn.departure.delay > 0">
                                +@{{ (conn.departure.delay)/60 }}&prime;
                            </span>
                            <span class="delay-route" ng-if="conn.departure.canceled > 0">
                                <i class="fa fa-exclamation-triangle"></i> canceled
                            </span>
                        </span>

                        <span class="planner-platform">
                            <span class="badge">@{{ conn.departure.platform }}</span>
                        </span>
                    </div>

                    <div ng-repeat="stop in conn.vias.via">

                        <span class="planner-train">

                        <i class="fa fa-train"></i> @{{stop.direction.name}}
                            <span class="small">&ndash; @{{stop.vehicle.replace("BE.NMBS.","")}}</span>
                            <img ng-src="/images/occupancy-@{{stop.departure.occupancy.name || 'unknown'}}.svg" alt="@{{conn.departure.occupancy.name || 'occupancy unknown'}}" height="16" width="16" />

                            @{{stop.occupancy.name}}

                            @include('_partials.route.feedback.stop')

                        </span>

                        <div class="planner-row">
                            <span class="planner-time">
                                <b>@{{(stop.arrival.time)*1000 | date:'HH:mm'}}</b>
                            </span>

                            <span class="planner-station">
                                <b>@{{ stop.station }}</b>
                            </span>

                            <span class="planner-platform">
                                <span class="badge">@{{ stop.arrival.platform }}</span>
                            </span>
                        </div>

                        <span class="planner-switch small text-muted">
                            @{{(stop.timeBetween/60)}} {{Lang::get('client.mins')}}
                            <br />
                            {{ Lang::get('client.fromPlatform')}} @{{ stop.arrival.platform }}
                            {{ Lang::get('client.toPlatform')}} @{{ stop.departure.platform }}
                        </span>

                        <div class="planner-row">
                            <span class="planner-time">
                                <b>@{{ (stop.departure.time)*1000 | date:'HH:mm' }}</b>
                            </span>

                            <span class="planner-station">
                                <b>@{{ stop.station}}</b>
                                <span class="delay-route" ng-if="stop.departure.delay > 0">
                                    +@{{ (stop.departure.delay)/60 }}&prime;
                                </span>
                                <span class="delay-route" ng-if="stop.departure.canceled > 0">
                                    {{ Lang::get('client.canceled') }}
                                </span>
                            </span>

                            <span class="planner-platform">
                                <span class="badge">@{{ stop.departure.platform }}</span>
                            </span>
                        </div>
                    </div>

                    <span class="planner-train">
                        <i class="fa fa-train"></i> @{{conn.arrival.direction.name}}
                        <span class="small">&ndash; @{{conn.arrival.vehicle.replace("BE.NMBS.","")}}</span>
                        <img ng-src="/images/occupancy-@{{conn.departure.occupancy.name || 'unknown'}}.svg" alt="@{{conn.departure.occupancy.name || 'occupancy unknown'}}" height="16" width="16" />

                        @include('_partials.route.feedback.connection')

                    </span>

                    <div class="planner-row">
                        <span class="planner-time">
                            <b>@{{ (conn.arrival.time)*1000 | date:'HH:mm' }}</b>
                        </span>

                        <span class="planner-station">
                            <b>@{{ conn.arrival.station}}</b>
                        </span>

                        <span class="planner-platform">
                            <span class="badge">@{{ conn.arrival.platform }}</span>
                        </span>
                    </div>

                </div>
            </div>
        </div>
        <div class="visible-print">
            <br/>
            <p>
                {{ Lang::get('client.thanksForUsing')}}
            </p>
        </div>
    </div>

    <div class="col-md-3 col-sm-4 hidden-print">
        <hr/>
        <div class="btn-group btn-block btn-botm">
            <a class="btn btn-default btn-50" ng-click="earlier()">
                <i class="fa fa-angle-left"></i>
                {{Lang::get('client.rideEarlier')}}
            </a>
            <a class="btn btn-default btn-50" ng-click="later()">
                {{Lang::get('client.rideLater')}}
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
        <div class="btn-group btn-block btn-botm">
            <a class="btn btn-default btn-50" ng-click="earliest()">
                <i class="fa fa-angle-left"></i>
                <i class="fa fa-angle-left"></i>
                {{Lang::get('client.earliestRide')}}
            </a>
            <a class="btn btn-default btn-50" ng-click="latest()">
                {{Lang::get('client.latestRide')}}
                <i class="fa fa-angle-right"></i>
                <i class="fa fa-angle-right"></i>
            </a>
        </div>
    </div>
</div>
