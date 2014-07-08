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
                <div class="col-md-6 col-md-offset-0">
                      <div class="panel panel-info">
                        <div class="panel-heading">Logged in</div>
                        <div class="panel-body">
                           <h2>Logged in!</h2>
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
