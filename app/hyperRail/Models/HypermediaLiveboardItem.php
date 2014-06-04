<?php

namespace hyperRail\Models;

class HypermediaLiveboardItem {

    // DEPARTURE DATA
    public $stationURL;                 // Station URL, to be built from: date + time + md5(trainID + destination)
    public $platform;                   // Platform to take at departure station
    public $delay;                      // Delay before the train departs, in minutes
    public $scheduledDepartureTime;     // Scheduled departure time

    // DESTINATION DATA
    public $destinationURL;    // The final destination of this train (URL)
    public $headsign;           // The final destination of this train (headsign, string)
    public $routeLabel;         // Label assigned to the train

    public function createStation($station_id, $date, $time, $train_id, $destination){
        $md5hash = md5($train_id . $destination);
        $this->$stationURL = _DOMAIN_ . "/NMBS/stations/" . $station_id . "/" . $date . $time . $md5hash;
    }

    /**
     * @int $delay (in minutes
     */
    public function setDelay($delay){
        $this->delay = $delay;
    }

    public function setScheduledDepartureTime($time){
        $this->scheduledDepartureTime = $time;
    }

    /**
     * @int $number
     */
    public function setPlatform($number){
        $this->platform = $number;
    }

    public function setDestinationURL($url){
        $this->destinationURL = $url;
    }

    public function setHeadsign($string){
        $this->headsign = $string;
    }

    public function setRouteLabel($string){
        $this->routeLabel = $string;
    }

    public function toJson(){
        $object = array(
            $this->stationURL => array(
                "http://semweb.mmlab.be/ns/rplod/delay" => $this->delay,
                "http://semweb.mmlab.be/ns/rplod/platform" => $this->platform,
                "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime" => $this->scheduledDepartureTime,
                "http://semweb.mmlab.be/ns/rplod/stop" => $this->destinationURL,
                "http://vocab.org/transit/terms/headsign" => $this->headsign,
                "http://semweb.mmlab.be/ns/rplod/routeLabel" => $this->routeLabel
            )
        );
        return $object;
    }

} 