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
                        <label for="departure">Station name</label>
                        <input type="text" ng-model="departure" placeholder="Type to search for a station" typeahead="station as station.name for station in stations.stations | filter:{name:$viewValue} | limitTo:5" typeahead-template-url="customTemplate.html" class="form-control input-lg">
                    </div>
                    <input type="submit" class="btn btn-default btn-lg btn-primary btn-wide" ng-click="save()" value="Watch liveboard" ng-show="departure">
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>