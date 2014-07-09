<?php

namespace hyperRail;
use hyperRail\Models\LiveboardItem;

/**
 * Class iRailFormatConverter
 * Converts the old JSON to objects.
 * @package hyperRail
 */
class iRailFormatConverter {
    /**
     * Converts a list of Liveboard items to an array.
     * @param $json
     * @param $id
     * @param string $format
     */
    public static function convertLiveboardData($json, $station_id, $format = "jsonld"){
        $liveboardCollection = array();
        $initialData = json_decode($json);
        // TODO: check if json can be decoded (is it an error?)
        // Set globals
        // (which data applies to all possible LiveboardItems we are going to create?)
        $stationName = $initialData->stationinfo->name;
        // Convert timestamp to date
        // TODO: count amount of elements, if null, throw error
        foreach ($initialData->departures->departure as $departure){
            $time = $departure->time;
            $liveboardItem = new LiveboardItem();
            $date = date('Ymd', $time);
            $time = date('Hi', $time);
            $vehicleShort = explode("BE.NMBS.", $departure->vehicle);
            // TODO: check if json can be decoded (is it an error?)
            $liveboardItem->fill($station_id, $date, $time, $vehicleShort[1], $departure->station, $departure->delay, date('c', $departure->time), $departure->platform);
            array_push($liveboardCollection, $liveboardItem->toArray());
        }
        $context = array(
            "delay" =>  "http://semweb.mmlab.be/ns/rplod/delay",
            "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
            "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
            "headsign" => "http://vocab.org/transit/terms/headsign",
            "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
            "stop" => array(
                "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                "@type" => "@id"
            ),
        );
        return array("@context" => $context, "@graph" => $liveboardCollection);
    }
} 