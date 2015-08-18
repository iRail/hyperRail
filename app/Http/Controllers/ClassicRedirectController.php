<?php

namespace App\Http\Controllers;

// todo Update docblocks.

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ClassicRedirectController extends Controller
{
    public function __construct()
    {
        $this->middleware('authencation');
    }

    /**
     * Redirect classic liveboards URL to the new location
     */
    public function redirectBoard()
    {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://" . Config::get('app.url-short') . "/liveboard");
    }
    /**
     * Redirect classic liveboards URL to the new location
     * and interpret the station name
     */
    public function redirectBoardSingleStation($station_provided_string)
    {
        $station_converted = \hyperRail\StationString::convertToId($station_provided_string);
        if ($station_converted != null) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://" . Config::get('app.url-short') . "/station/" . $station_converted->id);
        } else {
            return 'Liveboard for the following station: ' . $station_provided_string . ' was not found.';
        }
        return null;
    }
    /**
     * Redirect classic liveboards URL to the new location
     * and interpret two station names
     */
    public function redirectBoardTwoStations($station, $station2)
    {
        return 'Liveboards with multiple stations are no longer supported.';
    }
    /**
     * Redirect classic routing from the old iRail to the new
     * way the routing is done
     */
    public function redirectHomeRoute($departure_station, $destination_station)
    {
        $departure = \hyperRail\StationString::convertToId($departure_station);
        $destination = \hyperRail\StationString::convertToId($destination_station);
        if ($departure != null && $destination != null) {
            header("HTTP/1.1 301 Moved Permanently");
            return Redirect::to("https://" . Config::get('app.url-short') . "/route" .
                "?from=" . $departure->{'@id'} . "&to=" . $destination->{'@id'} . "&time="
                . date("Hi") . "&auto=true");
        } else {
            return "It looks like we couldn't convert your route request to the new format :(";
        }
    }
    public function redirectSettings()
    {
        return 'iRail has been changed. iRail no longer has a dedicated settings page.';
    }
}
