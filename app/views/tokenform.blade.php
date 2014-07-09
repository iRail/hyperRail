@extends('layouts.default')
@section('content')

<!DOCTYPE html>
<html lang="{{Config::get('app.locale');}}" ng-app="irailapp" ng-controller="StationLiveboardCtrl">
@include('core.head')
<body>
<div class="wrapper">
    <div id="main">
        @include('core.navigation')
        <div class="container">
            <div class="row login">
                <div class="col-md-4 col-md-offset-4">
                      <div class="panel panel-info">
                        <div class="panel-heading">Authorize application</div>
                        <div class="panel-body">
                            <form action="{{ $url }}" method="post">
                                <div class="form-group" style="text-align:center;">
                                    <h3>Do you authorize iRail?</h3>
                                </div>
                                <div class="form-group" style="text-align:center;">
                                    <input type="submit" class="btn btn-info btn-lg" name="authorized" value="Yes">
                                    <input type="submit" class="btn btn-default btn-lg" name="authorized" value="No">
                                </div>
                            </form>

                        </div>
                      </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('core.footer')
</body>
</html>
