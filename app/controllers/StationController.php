<?php

use ML\JsonLD\JsonLD;

class StationController extends \BaseController {

    public function index(){
        //TODO: remove code duplication and put this in BaseController
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json','text/html','*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = "text/html";
        //unless the negotiator has found something better for us
        if (isset($result)) {
            $val = $result->getValue();
        }        

        switch ($val){
            case "text/html":
                return View::make('stations.search');
            break;
            case "application/json":
            case "application/ld+json":
            default:
                return Response::make($this->getStations(Input::get("q")), 200)->header('Content-Type', 'application/ld+json')->header('Vary', 'accept');
            break;
        }
    }

    private function getStations($query = "") {
        if ($query && $query !== "") {
            //filter the stations on name match
            $stations = json_decode(File::get(app_path() . '/stations.json'));
            
            $newstations = new \stdClass;
            //could be implemented more efficiently using REDIS if we have more data later on. For now, this will do fine.
            $newstations->{"@id"} = $stations->{"@id"};
            $newstations->{"@context"} = $stations->{"@context"};
            $newstations->{"@graph"} = array();

            // dashes are the same as spaces
            $query = str_replace("\-","[\- ]",$query);
            $query = str_replace(" ","[\- ]",$query);

            foreach($stations->{"@graph"} as $station) {
                if(preg_match('/.*'. $query . '.*/i',$station->{"name"}, $match)){
                    $newstations->{"@graph"}[] = $station;
                }else if (isset($station->alternative)) {
                    if(is_array($station->alternative)) {
                        foreach($station->alternative as $alternative) {
                            if(preg_match('/.*('. $query . ').*/i',$alternative->{"@value"},$match)){
                                $newstations->{"@graph"}[] = $station;
                                break;
                            }
                        }
                    } else {
                        if(preg_match('/.*'. $query . '.*/i',$station->alternative->{"@value"})){
                            $newstations->{"@graph"}[] = $station;
                        }
                    }
                }
            }
            return json_encode($newstations,JSON_UNESCAPED_SLASHES);
        }else{
            return File::get(app_path() . '/stations.json');
        }
    }
        

    public function redirectToNMBSStations(){
        return Redirect::to('stations/NMBS');
    }

    public function liveboard($id){
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json', 'text/html', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = "text/html";
        //unless the negotiator has found something better for us
        if (isset($result)) {
            $val = $result->getValue();
        }

        switch ($val){
            case "text/html":
                try{
                    $station = \hyperRail\StationString::convertToString($id);
                    if ($station == null){
                        throw new StationConversionFailureException();
                    }
                    $data = array('station' => $station);
                    return Response::view('stations.liveboard', $data)->header('Content-Type', "text/html")->header('Vary', 'accept');
                    break;
                } catch(StationConversionFailureException $ex){
                    App::abort(404);
                }
            break;
            case "application/json":
            case "application/ld+json":
            default:
                try{
                    $stationStringName = \hyperRail\StationString::convertToString($id);
                    if ($stationStringName == null){
                        throw new StationConversionFailureException();
                    }
                    //Check for optional time parameters
                    $datetime = Input::get("datetime");
                    if (isset($datetime) && strtotime($datetime)) {
                        $datetime = strtotime($datetime);
                    } else {
                        $datetime = strtotime("now");
                    }
                    
                    $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json&date=" . date("mmddyy" ,$datetime)  . "&time=" . date("Hi", $datetime);
                    $data = file_get_contents($URL);
                    try{
                        $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $id);
                        $jsonLD = (string)json_encode($newData);
                        return Response::make($jsonLD, 200)->header('Content-Type', 'application/ld+json')->header('Vary', 'accept');
                    } catch(Exception $ex){
                        $error = (string)json_encode(array('error' => 'An error occured while parsing the data'));
                        return Response::make($error, 500)->header('Content-Type', 'application/json')->header('Vary', 'accept');
                    }
                } catch(StationConversionFailureException $ex){
                $error = (string)json_encode(array('error' => 'This station does not exist!'));
                return Response::make($error, 404)->header('Content-Type', 'application/json')->header('Vary', 'accept');
            }
            break;
        }
    }

    public function specificTrain($station_id, $liveboard_id){
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json', 'text/html', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();
        //mktime ([ int $hour = date("H") [, int $minute = date("i") [, int $second = date("s") [, int $month = date("n") [, int $day = date("j") [, int $year = date("Y") [, int $is_dst = -1 ]]]]]]] )
        //get the right 
        $datetime = substr($liveboard_id, 0,12);
        $datetime = strtotime($datetime);

        switch ($val){

            case "text/html":
                // Convert id to string for interpretation by old API
                $stationStringName = \hyperRail\StationString::convertToString($station_id);
                // Set up path to old api
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json&date=" . date("mmddyy" ,$datetime)  . "&time=" . date("Hi", $datetime);
                // Get the contents of this path
                $data = file_get_contents($URL);
                // Convert the data to the new liveboard object
                $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $station_id);
                // Read new liveboard object and return the page but load data
                foreach ($newData['@graph'] as $graph){
                    if (strpos($graph['@id'],$liveboard_id) !== false) {
                        return View::make('stations.departuredetail')->with('station', $graph)->with('departureStation', $stationStringName);
                    }
                }
                // If no match is found, attempt to look in the archive
                // Fetch file using curl
                $ch = curl_init("http://archive.irail.be/" . 'irail?subject=' . urlencode('http://irail.be/stations/NMBS/' . $station_id . '/departures/' . $liveboard_id));
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $request_headers[] = 'Accept: text/turtle';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $turtle = (curl_exec($ch));
                curl_close($ch);
                // Convert turtle to json-ld
                // Create a new graph
                $graph = new EasyRdf_Graph();
                if (empty($_REQUEST['data'])) {
                    // Load the sample information
                    $graph->parse($turtle, 'turtle');
                }
                // Export to JSON LD
                $format = EasyRdf_Format::getFormat('jsonld');
                $output = $graph->serialise($format);
                if (!is_scalar($output)) {
                    $output = var_export($output, true);
                }
                // First, define the context
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
                    "seeAlso" => array(
                        "@id" => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
                        "@type" => "@id",
                    )
                );
                // Next, encode the context as JSON
                $jsonContext = json_encode($context);
                // Compact the JsonLD by using @context
                $compacted = JsonLD::compact($output, $jsonContext);
                // Print the resulting JSON-LD!
                $urlToFind = 'NMBS/' . $station_id . '/departures/' . $liveboard_id;
                $stationDataFallback = json_decode(JsonLD::toString($compacted, true));
                foreach ($stationDataFallback->{'@graph'} as $graph){
                    if (strpos($graph->{'@id'},$urlToFind) !== false) {
                        return View::make('stations.departurearchive')->with('station', $graph)->with('departureStation', $stationStringName);
                    }
                }
            break;
            case "application/json":
            case "application/ld+json":
            default:
                $stationStringName = \hyperRail\StationString::convertToString($station_id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json&date=" . date("mmddyy" ,$datetime)  . "&time=" . date("Hi", $datetime);
                $data = file_get_contents($URL);
                $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $station_id);
                foreach ($newData['@graph'] as $graph){
                    if (strpos($graph['@id'],$liveboard_id) !== false) {
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
                        return array("@context" => $context, "@graph" => $graph);
                    }
                }
                // If no match is found, attempt to look in the archive
                // Fetch file using curl
                $ch = curl_init("http://archive.irail.be/" . 'irail?subject=' . urlencode('http://irail.be/stations/NMBS/' . $station_id . '/departures/' . $liveboard_id));
                curl_setopt($ch, CURLOPT_HEADER, 0);
                $request_headers[] = 'Accept: text/turtle';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $turtle = (curl_exec($ch));
                curl_close($ch);
                // Convert turtle to json-ld
                // Create a new graph
                $graph = new EasyRdf_Graph();
                if (empty($_REQUEST['data'])) {
                    // Load the sample information
                    $graph->parse($turtle, 'turtle');
                }
                // Export to JSON LD
                $format = EasyRdf_Format::getFormat('jsonld');
                $output = $graph->serialise($format);
                if (!is_scalar($output)) {
                    $output = var_export($output, true);
                }
                // First, define the context
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
                    "seeAlso" => array(
                        "@id" => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
                        "@type" => "@id",
                    )
                );
                // Next, encode the context as JSON
                $jsonContext = json_encode($context);
                // Compact the JsonLD by using @context
                $compacted = JsonLD::compact($output, $jsonContext);
                // Print the resulting JSON-LD!
                $urlToFind = 'NMBS/' . $station_id . '/departures/' . $liveboard_id;
                $stationDataFallback = json_decode(JsonLD::toString($compacted, true));
                foreach ($stationDataFallback->{'@graph'} as $graph){
                    if (strpos($graph->{'@id'},$urlToFind) !== false) {
                        return array("@context" => $context, "@graph" => $graph);
                    }
                }
                break;
        }
    }
}