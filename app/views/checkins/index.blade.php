@extends('layouts.default')
@section('header')
	@parent
@stop
@section('content')
	<div class="wrapper">
        <div id="main">
            @include('core.navigation')
            <div class="container">
                @if (count($checkins) == 0)
					<div class="alert alert-info">
						<p>You have no existing travel entries</p>
                	</div>
                @else
                	<div class="row results" ng-show="results">
            			<div class="col-md-12 col-sm-12">
	                        <p class="h1">Traveldiary</strong></p>
	                        <p>Below you'll find a list of all trains you have taken</p>
	                        <div class="list-group">
	                        	<div class="" id="past-checkins">
	                        		<a id="show-passed" class="btn btn-default btn-lg  btn-wide" onclick="$('#passed-checkins-body').toggle(200)">Show past checkins</a>
	                        		<div class="" id="passed-checkins-body" style="display: none;">
		                        	@foreach ($checkins as $checkin)
		                        	@if (strtotime($checkin['scheduledDepartureTime']) < strtotime('now')) 
		                        		<a class="list-group-item" href="{{$checkin['@id']}}">
			                                <span class="container33">
			                                	<span class="platform-left">
		                                            <span class="badge">{{ $checkin['platform'] }}</span>
		                                        </span>
		                                        <span class="station-middle">
		                                            <strong>
		                                                {{ $checkin['headsign']}}
		                                            </strong>
		                                        </span>
		                                        <span class="time-right">
		                                        	{{ $checkin['scheduledDepartureTime']}}
		                                        </span>
		                                      	<span class="delay-right">
			                                        <span class="delay">
			                                        	@if ($checkin['delay']/60 != 0)
			                                        		<p class="label label-warning">+{{ $checkin['delay']/60}}'</p>
			                                        	@endif
			                                        </span>
		                                      	</span>
				                            </span>
			                            </a>
		                        	@endif

	                        	@endforeach
	                        		</div>


	                        	@foreach ($checkins as $checkin)
		                        	@if (strtotime($checkin['scheduledDepartureTime']) >= strtotime('now')) 
		                        		<a class="list-group-item" href="{{$checkin['@id']}}">
			                                <span class="container33">
			                                	<span class="platform-left">
		                                            <span class="badge">{{ $checkin['platform'] }}</span>
		                                        </span>
		                                        <span class="station-middle">
		                                            <strong>
		                                                {{ $checkin['headsign']}}
		                                            </strong>
		                                        </span>
		                                        <span class="time-right">
		                                        	{{ $checkin['scheduledDepartureTime']}}
		                                        </span>
		                                      	<span class="delay-right">
			                                        <span class="delay">
			                                        	@if ($checkin['delay']/60 != 0)
			                                        		<p class="label label-warning">+{{ $checkin['delay']/60}}'</p>
			                                        	@endif
			                                        </span>
		                                      	</span>
				                            </span>
			                            </a>
		                        	@endif

	                        	@endforeach
	                        </div>
	                    </div>
	                </div>
                @endif
            </div>
        </div>
    </div>
@stop