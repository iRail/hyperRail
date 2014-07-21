<!DOCTYPE html>
<html lang="en" ng-app="irailapp" ng-controller="StationSearchCtrl">
@include('core.head')
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row max-w5 routeplanner view1">
                <div class="col-sm-12">
                    <script type="text/ng-template" id="customTemplate.html">
                        <a>
                            <span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
                        </a>
                    </script>
                    <div class="form-group">
                        <label for="departure">{{Lang::get('client.stationName')}}</label>
                        <input type="text" ng-model="departure" placeholder="{{Lang::get('client.stationSearchPlaceholder')}}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" class="form-control input-lg" typeahead-on-select='focus()'>
                    </div>
                    <a id="confirm" href="@{{departure['@id']}}" ng-show="departure['@id']" class="btn btn-primary btn-wide btn-lg bounceIn">{{Lang::get('client.viewLiveboard')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>
