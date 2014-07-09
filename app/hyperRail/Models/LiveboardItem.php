<?php

namespace hyperRail\Models;
use stdClass;

class LiveboardItem {
    // DEPARTURE DATA
    public $stationURL;                 // Station URL, to be built from: date + time + md5(trainID + destination)
    public $platform;                   // Platform to take at departure station
    public $delay;                      // Delay before the train departs, in minutes
    public $scheduledDepartureTime;     // Scheduled departure time
    // DESTINATION DATA
    public $destinationURL;    // The final destination of this train (URL)
    public $headsign;           // The final destination of this train (headsign, string)
    public $routeLabel;         // Label assigned to the train

    public function fill($stationId, $requestDate, $requestTime, $routeLabel, $headSign,
                                        $delay, $time, $platform){
        $this->headsign = $headSign;
        $this->routeLabel = preg_replace("/([A-Z]{1,2})(\d+)/", "$1 $2", $routeLabel);
        $md5hash = md5($this->routeLabel . $this->headsign);
        $this->stationURL = "http://" . _DOMAIN_ . "/stations/NMBS/" . $stationId . "/departures/" . $requestDate . $requestTime . $md5hash;
        $this->delay = $delay;
        $this->scheduledDepartureTime = $time;
        $this->platform = $platform;
        $this->destinationURL = "http://" . _DOMAIN_ . "/stations/NMBS/" . $stationId;
    }

    public function toArray(){
        $dataArray = array(
            "@id" => $this->stationURL,
            "delay" => $this->delay,
            "platform" => $this->platform,
            "scheduledDepartureTime" => $this->scheduledDepartureTime,
            "stop" => $this->destinationURL,
            "headsign" => $this->headsign,
            "routeLabel" => $this->routeLabel
        );
        return $dataArray;
    }
} 