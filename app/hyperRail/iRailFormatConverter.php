<?php

namespace hyperRail;

/**
 * Class iRailFormatConverter
 * Converts the old JSON to new JSON.
 * @package hyperRail
 */
class iRailFormatConverter {
    public static function convertRoutePlanningData($json, $id, $format = "jsonld"){
    }
    public static function convertLiveboardData($json, $id, $format = "jsonld"){
        // First, json_decode the data
        $initialData = json_decode($json);
        // $departures = array();
        var_dump(json_decode($json));
        exit;
    }
} 