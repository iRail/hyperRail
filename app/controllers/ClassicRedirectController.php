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
    public function redirectBoardSingleStation($station){
        // TODO: convert station string to station id using Nicola's list of stations
        // TODO: redirect to the proper url
        return 'You requested the liveboard for station: ' . $station;
    }

    /**
     * Redirect classic liveboards URL to the new location
     * and interpret two station names
     */
    public function redirectBoardTwoStations($station, $station2){
        return 'You requested the liveboard for stations: ' . $station . " & " . $station2;
    }

    /**
     * Redirect classic routing from the old iRail to the new
     * way the routing is done
     */
    public function redirectHomeRoute($departure_station, $destination_station){

    }

    public function redirectSettings(){
        return 'iRail has been changed. iRail no longer has a dedicated settings page.';
    }

}