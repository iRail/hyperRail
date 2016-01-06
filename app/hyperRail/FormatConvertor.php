<?php

namespace App\hyperRail;

use App\hyperRail\Models\LiveboardItem;

/**
 * Class iRailFormatConverter
 * Converts the old JSON to objects.
 */
class FormatConverter
{
    /**
     * Converts a list of Liveboard items to an array.
     * @param $json
     * @param $station_id
     * @param string $format
     * @return array
     * @internal param $id
     */
    public static function convertLiveboardData($json, $station_id, $format = 'jsonld')
    {
        $liveboardCollection = [];
        $initialData = json_decode($json);
        // TODO: check if json can be decoded (is it an error?)
        // Set globals
        // (which data applies to all possible LiveboardItems we are going to create?)
        $stationName = $initialData->stationinfo->name;
        // Convert timestamp to date
        // TODO: count amount of elements, if null, throw error
        foreach ($initialData->departures->departure as $departure) {
            $time = $departure->time;
            $liveboardItem = new LiveboardItem();
            $date = date('Ymd', $time);
            $time = date('Hi', $time);
            $vehicleShort = explode('BE.NMBS.', $departure->vehicle);
            $canceled = $departure->canceled;

            $liveboardItem->fill(
                $station_id,
                $date,
                $time,
                $vehicleShort[1],
                $departure->station,
                $departure->delay,
                date('c', $departure->time),
                $departure->platform,
                $canceled
            );

            array_push($liveboardCollection, $liveboardItem->toArray());
        }
        $context = [
            'delay' => 'http://semweb.mmlab.be/ns/rplod/delay',
            'platform' => 'http://semweb.mmlab.be/ns/rplod/platform',
            'scheduledDepartureTime' => 'http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime',
            'headsign' => 'http://vocab.org/transit/terms/headsign',
            'routeLabel' => 'http://semweb.mmlab.be/ns/rplod/routeLabel',
            'stop' => [
                '@id' => 'http://semweb.mmlab.be/ns/rplod/stop',
                '@type' => '@id',
            ],
        ];

        return ['@context' => $context, '@graph' => $liveboardCollection];
    }
}
