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
                                <span class="">
                                    @{{ (conn.departure.time)*1000 | date:'HH:mm' }}
                                    &rarr;
                                    @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                                </span>
                                <span class="text-muted">
                                    (@{{ formatDuration( ((conn.arrival.time-conn.departure.time)/60) ) }})
                                </span>
                                <span class="planner-change text-muted" ng-if="conn.vias.number > 0">
                                    <br />@{{ conn.vias.number }} transfers
                                </span>
                            </span>
                            <span class="pull-right">
                                <span class="badge">
                                    @{{ conn.departure.platform }}
                                </span>
                            </span>
                        </a>
                    </h2>
                </div>

                <div id="result-@{{connections.indexOf(conn)}}" class="panel-body panel-collapse collapse" ng-class="{in : $first}"  >

                    <div class="clearfix">
                        <span class="planner-time">
                            <b>@{{ (conn.departure.time)*1000 | date:'HH:mm' }}</b>
                            <span class="delay-route" ng-if="conn.departure.delay > 0">
                                +@{{ (conn.departure.delay)/60 }}&prime;
                            </span>
                            <span class="delay-route" ng-if="conn.departure.canceled > 0">
                                <i class="fa fa-exclamation-triangle"></i> canceled
                            </span>
                        </span>

                        <span class="planner-station">
                            <b>@{{ conn.departure.station}}</b>
                        </span>

                        <span class="planner-platform badge pull-right">
                            @{{ conn.departure.platform }}
                        </span>
                    </div>

                    <div ng-repeat="stop in conn.vias.via">

                        <span class="planner-train"><i class="fa fa-train"></i> @{{stop.direction.name}} <span class="small">&ndash; @{{stop.vehicle.replace("BE.NMBS.","")}}</span></span>

                        <hr />

                        <span class="planner-time">
                            @{{(stop.arrival.time)*1000 | date:'HH:mm'}}
                        </span>

                        <span class="planner-station">
                            <span class="small text-muted">@{{(stop.timeBetween/60)}} {{Lang::get('client.mins')}}</span>
                        </span>

                        <br />

                        <span class="planner-time">
                            <b>@{{ (stop.departure.time)*1000 | date:'HH:mm' }}</b>
                            <span class="delay-route" ng-if="stop.departure.delay > 0">
                                +@{{ (stop.departure.delay)/60 }}&prime;
                            </span>
                            <span class="delay-route" ng-if="stop.departure.canceled > 0">
                                canceled
                            </span>
                        </span>

                        <span class="planner-station">
                            <b>@{{ stop.station}}</b>
                        </span>

                        <span class="planner-platform badge pull-right">
                            @{{ stop.departure.platform }}
                        </span>

                    </div>

                    <span class="planner-train"><i class="fa fa-train"></i> @{{conn.arrival.direction.name}} <span class="small">&ndash; @{{conn.arrival.vehicle.replace("BE.NMBS.","")}}</span></span>

                    <hr />

                    <span class="planner-time">
                            @{{ (conn.arrival.time)*1000 | date:'HH:mm' }}
                    </span>

                    <span class="planner-station">
                            @{{ conn.arrival.station}}
                    </span>

                    <span class="planner-platform badge pull-right">
                        @{{ conn.arrival.platform }}
                    </span>

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
