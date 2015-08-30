<?php

namespace app\hyperRail\Models;

class LiveboardItem
{
    // DEPARTURE DATA
    public $stationURL;                 // Station URL, to be built from: date + time + md5(trainID + destination)
    public $platform;                   // Platform to take at departure station
    public $delay;                      // Delay before the train departs, in minutes
    public $scheduledDepartureTime;     // Scheduled departure time
    // DESTINATION DATA
    public $destinationURL;    // The final destination of this train (URL)
    public $headsign;           // The final destination of this train (headsign, string)
    public $routeLabel;         // Label assigned to the train

    /**
     * Set the values for this LiveboardItem.
     *
     * @param $stationId
     * @param $requestDate
     * @param $requestTime
     * @param $routeLabel
     * @param $headSign
     * @param $delay
     * @param $time
     * @param $platform
     */
    public function fill($stationId, $requestDate, $requestTime, $routeLabel, $headSign, $delay, $time, $platform)
    {
        $this->headsign = $headSign;
        $this->routeLabel = preg_replace("/([A-Z]{1,2})(\d+)/", '$1 $2', $routeLabel);
        $this->stationURL = $this->getStationUrl($stationId, $requestDate, $requestTime);
        $this->delay = $delay;
        $this->scheduledDepartureTime = $time;
        $this->platform = $platform;
        $this->destinationURL = route('station.index', [
            'id' => $stationId,
        ]);
    }

    /**
     * Converts this object to a JSON-LD compatible array (using @id).
     *
     * @return array
     */
    public function toArray()
    {
        $dataArray = [
            '@id' => $this->stationURL,
            'delay' => $this->delay,
            'platform' => $this->platform,
            'scheduledDepartureTime' => $this->scheduledDepartureTime,
            'stop' => $this->destinationURL,
            'headsign' => $this->headsign,
            'routeLabel' => $this->routeLabel,
        ];

        return $dataArray;
    }

    private function getStationUrl($stationId, $requestDate, $requestTime)
    {
        $md5hash = md5($this->routeLabel.$this->headsign);

        return route('stations.departures.hash', [
            'id' => $stationId,
            'trainHash' => $requestDate.$requestTime.$md5hash,
        ]);
    }
}
