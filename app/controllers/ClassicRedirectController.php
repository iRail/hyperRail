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
        $json = file_get_contents('http://' . _DOMAIN_ . '/data/stations.json');
        $data = json_decode($json);
        foreach($data->stations as $station){
            // TODO: write an array with station name alternates
            // This also solves multilanguage issues since the strings are simply converted
            // to the proper id :) This way, we can make iRail multilanguage!
            /*
             * FOR EXAMPLE:
             *
             * $alternates = array(
             * "Gent-Sint-Pieters" => array('Ghent-Sint-Pieters', 'Gent Sint Pieters', 'Ghent Sint Pieters')
             * )
             */
            // TODO: write a function that loops through station name alternates
            // If the provided searchstring is contained within an item
            // Redirect to that station
            if (strpos($station->name,$station_provided_string) !== false) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: http://" . _DOMAIN_ . "/station/" . $station->id);
            }
        }
        return 'Liveboard for the following station: ' . $station_provided_string . ' was not found.';
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