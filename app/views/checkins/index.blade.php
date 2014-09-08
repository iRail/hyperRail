@extends('layouts.default')
@section('header')
	@parent
@stop
@section('content')
	<div class="wrapper">
        <div id="main">
            @include('core.navigation')                
	            <div class="container">
		            @include('_partials.route.checkins')
		        </div>                       	
        </div>
    </div>
@stop