<?php

namespace hyperRail;

class StationString {

    /**
     * Converts a station string to a station id. If the string is not converted,
     * null is returned.
     * @param $string
     * @return string or null
     */
    public static function convertToId($string){
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
            if (strpos($station->name,$string) !== false) {
                return $station;
            }
        }
        return null;
    }
} 