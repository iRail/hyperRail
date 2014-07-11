@extends('layouts.default')
@section('header')
	@parent
@stop
@section('content')
	<div class="wrapper" ng-app="irailapp" ng-controller="StationLiveboardCtrl">
        <div id="main">
            @include('core.navigation')
            <div class="container">
				<div>
				</div>
            </div>
        </div>
    </div>
@stop