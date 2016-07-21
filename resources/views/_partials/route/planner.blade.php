<div class="routeplanner view1" ng-show="planning results">

    <script type="text/ng-template" id="customTemplate.html">
        <a>
            <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
        </a>
    </script>

    <div class="row">

        <div class="col-sm-6">
            <div class="form-group">
                <div class="input-group-oneliner has-affix">
                    <label for="departureStation" class="input-group-label">{!! Lang::get('client.fromStation')!!}</label>
                    <input type="text" id="departureStation" ng-model="departure" placeholder="{!! Lang::get('client.typeFromStation')!!}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" class="form-control input-lg" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                    <a class="input-group-affix" ng-click="reverse()"><i class="fa fa-exchange"></i> <span class="sr-only">{{Lang::get('client.reverse')}}</span></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="input-group-oneliner">
                    <label for="destinationStation" class="input-group-label">{!! Lang::get('client.toStation')!!}</label>
                    <input type="text" id="destinationStation" ng-model="destination" placeholder="{!! Lang::get('client.typeToStation')!!}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" typeahead-on-select='focusOnConfirm()' class="form-control input-lg" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-sm-9">
            <div class="panel-group" id="accordion">
                <div class="panel panel-default">
                    <div class="panel-heading p0">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseCalendar" class="btn btn-ghost btn-lg btn-block">
                            <span ng-show="timeoption == 'depart'">{!! Lang::get('client.departureAtHour')!!}</span>
                            <span ng-show="timeoption == 'arrive'">{!! Lang::get('client.arrivalAtHour')!!}</span>
                            &nbsp;
                            <i class="fa fa-clock-o"></i>&nbsp;@{{mytime | date:'HH:mm' }}
                            &nbsp;
                            <i class="fa fa-calendar"></i>&nbsp;@{{mydate | date:'yyyy–MM–dd' }}
                        </a>
                    </div>
                    <div id="collapseCalendar" class="panel-collapse collapse">
                        <div class="panel-body">
                            <label for="departureTime" class="sr-only">{!! Lang::get('client.chooseTime')!!}</label>

                            <label class="radio-inline"><input type="radio" name="departureTime" ng-model="timeoption" value="depart">{!! Lang::get('client.departureAtHour')!!}</label>
                            <label class="radio-inline"><input type="radio" name="departureTime" ng-model="timeoption" value="arrive">{!! Lang::get('client.arrivalAtHour')!!}</label>

                            <timepicker ng-model="mytime" ng-change="changed()" minute-step="15" show-meridian="ismeridian"></timepicker>
                            <div class="datepicker">
                                <datepicker ng-class="time" ng-model="mydate" show-weeks="false" starting-day="1"></datepicker>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <button type="submit" id="confirm" class="btn btn-lg btn-primary btn-block" ng-click="save()" ng-disabled="departure == destination">
                {!! Lang::get('client.confirmSearch')!!}
                <i class="fa fa-angle-right"></i>
            </button>
        </div>
    </div>

    <div class="alert alert-danger" ng-show="data === null">
        <p ng-show="stationnotfound === true">
            {!! Lang::get('client.errorCheckInput')!!}
        </p>
    </div>

    <div class="alert alert-info" ng-show="departure == destination && departure != null && destination != null">
        <p>
            {!!  Lang::get('client.stationsIdentical')!!}
        </p>
    </div>

</div>
