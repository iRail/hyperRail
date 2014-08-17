<div class="row routeplanner view1" ng-show="planning">
	<div class="col-sm-6">
		<script type="text/ng-template" id="customTemplate.html">
			<a>
				<span bind-html-unsafe="match.label | typeaheadHighlight:query"></span>
			</a>
		</script>
		<div class="form-group">
			<label for="departure">{{Lang::get('client.fromStation')}}</label>
			<div class="input-group">
			<input type="text" ng-model="departure" placeholder="{{Lang::get('client.typeFromStation')}}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" class="form-control input-lg" tabindex="1">
			<a class="input-group-addon" ng-show="departure['@id']" href="@{{departure['@id']}}" target="_blank" data-toggle="tooltip" data-placement="left" title="{{Lang::get('client.viewLiveboard')}}"><i class="fa fa-clock-o"></i></a>
			<span class="input-group-addon" ng-hide="departure['@id']"></span>
			</div>
		</div>
		<div class="form-group">
			<label for="destination">{{Lang::get('client.toStation')}}</label>
			<div class="input-group">
				<input type="text" ng-model="destination" placeholder="{{Lang::get('client.typeToStation')}}" typeahead="station as station.name for station in getStations($viewValue)" typeahead-template-url="customTemplate.html" typeahead-on-select='focusOnConfirm()' class="form-control input-lg" tabindex="2">
				<a class="input-group-addon btn" ng-show="destination['@id']" href="@{{destination['@id']}}" target="_blank" data-toggle="tooltip" data-placement="left" title="{{Lang::get('client.viewLiveboard')}}"><i class="fa fa-clock-o"></i></a>
				<span class="input-group-addon" ng-hide="destination['@id']"></span>
			</div>
		</div>
		<label for="destination">{{Lang::get('client.chooseDate')}}</label>
		<div class="datepicker">
			<datepicker ng-class="time" ng-model="mydate" show-weeks="false" ></datepicker>
		</div>
		<br/>
	</div>
	<div class="col-sm-6">
		<label for="destination">{{Lang::get('client.chooseTime')}}</label>
		<select class="form-control input-lg timepicker" ng-model="timeoption">
			<option value="depart">{{Lang::get('client.departureAtHour')}}</option>
			<option value="arrive">{{Lang::get('client.arrivalAtHour')}}</option>
		</select>
		<timepicker ng-model="mytime" ng-change="changed()" minute-step="15" show-meridian="ismeridian"></timepicker>
		<br/>
		<input type="submit" id="confirm" class="btn btn-default btn-lg btn-primary btn-wide" ng-click="save()" value="{{Lang::get('client.confirmSearch')}}" ng-hide="departure == destination">
		<div class="alert alert-danger" ng-show="data === null">
			<p ng-show="stationnotfound === true">{{Lang::get('client.errorCheckInput')}}</p>
		</div>
		<div class="alert alert-info" ng-show="departure == destination && departure != null && destination != null">
		   <p>{{Lang::get('client.stationsIdentical')}}</p>
		</div>
	</div>
</div>