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

							<div class="list-group">
	                            <a class="list-group-item" ng-repeat="checkin in checkins" ng-href="@{{checkin['departure']}}">
	                                  <span class="container33 liveboard-list">
	                                        <span class="platform-left" >
	                                            <span class="badge">@{{$checkin}}</span>
	                                        </span>
	                                  </span>
	                            </a>
	                        </div>


	                        
	                        <div class="list-group">
	                        	@foreach ($checkins as $checkin)
	                        		<a class="list-group-item" href="{{$checkin->departure}}">
		                                <span class="container33">
											<p>{{$checkin}}</p>
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