@if (count($checkins) == 0)
<div class="alert alert-info">
	<p>{{Lang::get('client.ErrTravelDiary')}}</p>
</div>
@else
<div class="row results" ng-show="results">
	<div class="col-md-12 col-sm-12">
		<p class="h1">{{Lang::get('client.traveldiary')}}</strong></p>
		<div class="list-group">
			<div class="" id="past-checkins">
				<a id="show-passed" class="btn btn-default btn-lg  btn-wide" onclick="$('#passed-checkins-body').toggle(200)">
					{{Lang::get('client.showPastCheckins')}}
				</a>
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
</div>
@endif
