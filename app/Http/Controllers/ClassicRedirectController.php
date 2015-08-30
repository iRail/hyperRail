<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;

class ClassicRedirectController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    /**
     * Redirect classic liveboards URL to the new location.
     */
    public function redirectBoard()
    {
        //return Redirect::to("http://" . Config::get('app.url-short') . "/liveboard", 301);
        return Redirect::action('RouteController@index', [], 301);
    }

    /**
     * Redirect classic liveboards URL to the new location
     * and interpret the station name.
     *
     * @param string, $station_provided_string.
     *
     * @return null|string
     */
    public function redirectBoardSingleStation($station_provided_string)
    {
        $station_converted = \hyperRail\StationString::convertToId($station_provided_string);
        if ($station_converted != null) {
            return Redirect::route('station.index', ['id' => $station_converted->id], 301);
        } else {
            return 'Liveboard for the following station: '.$station_provided_string.' was not found.';
        }

        return;
    }

    /**
     * Redirect classic liveboards URL to the new location
     * and interpret two station names.
     *
     * @param string $station  The first station.
     * @param string $station2 The second station.
     *
     * @return string
     */
    public function redirectBoardTwoStations($station, $station2)
    {
        return 'Liveboards with multiple stations are no longer supported.';
    }

    /**
     * Redirect classic routing from the old iRail to the new
     * way the routing is done.
     *
     * @param $departure_station,   the departure station.
     * @param $destination_station, the destination station.
     *
     * @return string
     */
    public function redirectHomeRoute($departure_station, $destination_station)
    {
        $departure = \hyperRail\StationString::convertToId($departure_station);
        $destination = \hyperRail\StationString::convertToId($destination_station);
        if ($departure != null && $destination != null) {
            // header("HTTP/1.1 301 Moved Permanently");
            //return Redirect::to("https://" . Config::get('app.url-short') . "/route" .
            //  "?from=" . $departure->{'@id'} . "&to=" . $destination->{'@id'} . "&time="
            //  . date("Hi") . "&auto=true");

            // return to Main route as $departure->{'@id'} returns full URL
            return Redirect::action('RouteController@index', [], 301);
        } else {
            return "It looks like we couldn't convert your route request to the new format :(";
        }
    }

    public function redirectSettings()
    {
        return 'iRail has been changed. iRail no longer has a dedicated settings page.';
    }
}
