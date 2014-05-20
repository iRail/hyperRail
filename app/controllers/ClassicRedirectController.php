<?php

class ClassicRedirectController extends \BaseController {

    /**
     * Redirect classic liveboards URL to the new location
     */
    public function redirectBoard(){
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://" . _DOMAIN_ . "/liveboard");
    }

    /**
     * Redirect classic liveboards URL to the new location
     * and interpret the station name
     */
    public function redirectBoardSingleStation($station_provided_string){
        $station_converted = \hyperRail\StationString::convertToId($station_provided_string);
        if ($station_converted != null){
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: https://" . _DOMAIN_ . "/station/" . $station_converted->id);
        }
        else{
            return 'Liveboard for the following station: ' . $station_provided_string . ' was not found.';
        }
        return null;
    }

    /**
     * Redirect classic liveboards URL to the new location
     * and interpret two station names
     */
    public function redirectBoardTwoStations($station, $station2){
       return 'Liveboards with multiple stations are no longer supported.';
    }

    /**
     * Redirect classic routing from the old iRail to the new
     * way the routing is done
     */
    public function redirectHomeRoute($departure_station, $destination_station){
        $departure = \hyperRail\StationString::convertToId($departure_station);
        $destination = \hyperRail\StationString::convertToId($destination_station);
        if ($departure != null && $destination != null){
            header("HTTP/1.1 301 Moved Permanently");
            header( "Location: https://" . _DOMAIN_ . "/route" . "?mode=train" . "&from=" . $departure->id . "&to=" . $destination->id . "&time=" . date("hi") );
        }
        return "It looks like we couldn't convert your route request to the new format :(";
    }

    public function redirectSettings(){
        return 'iRail has been changed. iRail no longer has a dedicated settings page.';
    }

}