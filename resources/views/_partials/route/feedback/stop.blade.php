{{-- Show the feedback form 10 minutes before connection departure time --}}
<div class="dropdown" ng-show="{{ time() + (10 * 60) }} >= @{{conn.departure.time }}">
    <button class="btn btn-link btn-link-subtle btn-xs dropdown-toggle" type="button" id="dropdownMenu2" aria-haspopup="true" aria-expanded="false">
        {{ Lang::get('client.howBusyIsThisTrain') }}
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
        <li>
            <a href="#"
            ng-click="selectOccupancy($event)"
            data-occupancy="high"
            data-from="@{{stop.stationinfo['@id']}}"
            data-date="@{{stop.departure.time}}"
            data-vehicle="@{{stop.departure.vehicle}}"
            data-connection="@{{stop.departure.departureConnection}}">
                <i class="occupancy-icon occupancy-high-16"></i>
                {{ Lang::get('client.highOccupied') }}
            </a>
        </li>
        <li>
            <a href="#"
            ng-click="selectOccupancy($event)"
            data-occupancy="medium"
            data-from="@{{stop.stationinfo['@id']}}"
            data-date="@{{stop.departure.time}}"
            data-vehicle="@{{stop.departure.vehicle}}"
            data-connection="@{{stop.departure.departureConnection}}">
                <i class="occupancy-icon occupancy-medium-16"></i>
                {{ Lang::get('client.mediumOccupied') }}
            </a>
        </li>
        <li>
            <a href="#"
            ng-click="selectOccupancy($event)"
            data-occupancy="low"
            data-from="@{{stop.stationinfo['@id']}}"
            data-date="@{{stop.departure.time}}"
            data-vehicle="@{{stop.departure.vehicle}}"
            data-connection="@{{stop.departure.departureConnection}}">
                <i class="occupancy-icon occupancy-low-16"></i>
                {{ Lang::get('client.lowOccupied') }}
            </a>
        </li>
    </ul>
</div>
