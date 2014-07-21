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
	                        	@foreach ($checkins as $checkin)
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
		                                        	&nbsp;&nbsp;{{ $checkin['delay']/60}}
		                                        </span>
	                                      	</span>
			                            </span>
		                            </a>
	                        	@endforeach
	                        </div>
	                    </div>
	                </div>
                @endif
            </div>
        </div>
    </div>
@stop