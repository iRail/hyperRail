<?php
namespace hyperRail;

use Illuminate\Support\Facades\File;

class StationString
{
    /**
     * Converts a station string to a station id. If the string cannot be converted,
     * null is returned.
     * @param $string
     * @return string or null
     */
    public static function convertToId($string)
    {
        // Fetch stations list by http://github.com/iRail/stations to compare the station string with
        $json = File::get(app_path() . "/stations.json");
        $data = json_decode($json);
        // $alternates = array();
        // For each station in the array of stations, attempt comparison
        foreach ($data->{"@graph"} as $station) {
            /*
             * Write an array with station name alternates
             *
             * This also solves multilanguage issues since the strings are simply converted
             * to the proper id :) This way, we can make iRail multilanguage!
             *
             */
            /* If we can find the station name in the string,
             * we can return the station data if we get a hit!
             */
            if (strpos($station->name, $string) !== false) {
                return $station;
            }
            /*
             * Assuming the main name was not a hit, try the alternates
             * If an alternate value matches, return the station
             */
            if (isset($station->alternative)) {
                foreach ($station->alternative as $alternate) {
                    if (strpos($alternate->{"@value"}, $string) !== false) {
                        return $station;
                    }
                }
            }
        }
        // If there is no station id found for this string...
        // we return null. Cool thing about this method is that our strings
        // no not need to be complete. 'Pieters' will take me to 'Ghent-Sint-Pieters'!
        return null;
    }

    /**
     * Converts a station id to a station string. If the string cannot be converted,
     * null is returned.
     * @param $string
     * @return string or null
     */
    public static function convertToString($string)
    {
        // Fetch stations list to compare the station string with
        $json = File::get(app_path() . "/stations.json");
        $data = json_decode($json);
        // For each station in the array of stations, attempt comparison
        foreach ($data->{"@graph"} as $station) {
            /*
             * TODO: write an array with station name alternates
             *
             * This also solves multilanguage issues since the strings are simply converted
             * to the proper id :) This way, we can make iRail multilanguage!
             *
             * $alternates = array(
             * "Gent-Sint-Pieters" => array('Ghent-Sint-Pieters', 'Gent Sint Pieters', 'Ghent Sint Pieters')
             * )
             */
            /*
             * Assuming we have a list of station name alternates, we can do even more
             * comparisons to ensure that this process is functional.
             * TODO: write a function that loops through station name alternates
             */
            /* If we can find the station name in the string of Nicola's records,
             * we can return the station data if we get a hit!
             * Arguably we need to check if there are multiple hits:
             * TODO: check for multiple hits when using strpos()
             */
            if (strpos($station->{"@id"}, $string) !== false) {
                return $station;
            }
        }
        return null;
    }
}
