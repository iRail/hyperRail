<div class="routeplanner view1" ng-show="planning">

    <script type="text/ng-template" id="customTemplate.html">
        <a>
            <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
        </a>
    </script>

    <div class="row">

        <div class="col-sm-6">
            <div class="form-group">
                <div class="input-group">
                    <label for="departureStation" class="input-group-addon">{!! Lang::get('client.fromStation')!!}</label>
                    <input type="text" id="departureStation" ng-model="departure" placeholder="{!! Lang::get('client.typeFromStation')!!}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" class="form-control input-lg" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                    <a class="input-group-addon" ng-show="departure['@id']" href="@{{departure['@id']}}" data-toggle="tooltip" data-placement="left" title="{!! Lang::get('client.viewLiveboard')!!}">
                        <i class="fa fa-clock-o"></i>
                        <span class="sr-only">{!! Lang::get('client.viewLiveboard')!!}</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="input-group">
                    <label for="destinationStation" class="input-group-addon">{!! Lang::get('client.toStation')!!}</label>
                    <input type="text" id="destinationStation" ng-model="destination" placeholder="{!! Lang::get('client.typeToStation')!!}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" typeahead-on-select='focusOnConfirm()' class="form-control input-lg" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                    <a class="input-group-addon btn" ng-show="destination['@id']" href="@{{destination['@id']}}" data-toggle="tooltip" data-placement="left" title="{!! Lang::get('client.viewLiveboard')!!}">
                        <i class="fa fa-clock-o"></i>
                        <span class="sr-only">{!! Lang::get('client.viewLiveboard')!!}</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-sm-6">
            <label for="departureTime" class="sr-only">{!! Lang::get('client.chooseTime')!!}</label>
            <select id="departureTime" class="form-control input-lg timepicker" ng-model="timeoption">
                <option value="depart">{!! Lang::get('client.departureAtHour')!!}</option>
                <option value="arrive">{!! Lang::get('client.arrivalAtHour')!!}</option>
            </select>
        </div>

        <div class="col-sm-6">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseCalendar">
                                {{-- {!! Lang::get('client.chooseDate')!!} --}}
                                @{{mydate | date:'yyyy-MM-dd' }} @{{mytime | date:'HH:mm' }}
                            </a>
                        </h4>
                    </div>
                    <div id="collapseCalendar" class="panel-collapse collapse">
                        <div class="panel-body">
                            <div class="datepicker">
                                <datepicker ng-class="time" ng-model="mydate" show-weeks="false" starting-day="1"></datepicker>
                            </div>
                            <timepicker ng-model="mytime" ng-change="changed()" minute-step="15" show-meridian="ismeridian"></timepicker>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <br/>

    <input type="submit" id="confirm" class="btn btn-default btn-lg btn-primary btn-wide" ng-click="save()" value="{!! Lang::get('client.confirmSearch')!!}" ng-hide="departure == destination">

    <div class="alert alert-danger" ng-show="data === null">
        <p ng-show="stationnotfound === true">{!! Lang::get('client.errorCheckInput')!!}</p>
    </div>

    <div class="alert alert-info" ng-show="departure == destination && departure != null && destination != null">
        <p>{!!  Lang::get('client.stationsIdentical')!!}</p>
    </div>

</div>
