<?php

class StationController extends \BaseController {

    public function index(){
        // TODO: content delegation with list of all stations
        return View::make('stations.search');
    }

    public function redirectToNMBSStations(){
        return Redirect::to('/NMBS/stations');
    }

    public function liveboard($id){

        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();
        switch ($val){
            case "text/html":
                $station = \hyperRail\StationString::convertToString($id);
                $data = array('station' => $station);
                return Response::view('stations.liveboard', $data)->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
            case "application/ld+json":
                $stationStringName = \hyperRail\StationString::convertToString($id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                $data = file_get_contents($URL);
                $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $id);
                $jsonLD = (string)json_encode($newData);
                return Response::make($jsonLD, 200)->header('Content-Type', 'application/ld+json')->header('Vary', 'accept');
                break;
            /*case "text/turtle":
                $stationStringName = \hyperRail\StationString::convertToString($id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                $data = file_get_contents($URL);
                $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $id);
                $jsonLD = (string)json_encode($newData);
                $graph = new EasyRdf_Graph();
                // NO PARSER YET IN STABLE :(
                $graph->parse($jsonLD, EasyRdf_Format::getFormat('json-ld'));
                $output = $graph->serialise(EasyRdf_Format::getFormat('turtle'));
                return Response::make($output, 200)->header('Content-Type', 'text/turtle')->header('Vary', 'accept');
                break;
            */
            default:
                $station = \hyperRail\StationString::convertToString($id);
                $data = array('station' => $station);
                return Response::view('stations.liveboard', $data)->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
        }
    }

    public function specificTrain($station_id, $liveboard_id){
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();
        switch ($val){
            case "text/html":
                    $stationStringName = \hyperRail\StationString::convertToString($station_id);
                    $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                    $data = file_get_contents($URL);
                    $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $station_id);
                    foreach ($newData['@graph'] as $graph){
                        if (strpos($graph['@id'],$liveboard_id) !== false) {
                            return View::make('stations.departuredetail')->with('station', $graph)->with('departureStation', $stationStringName);
                        }
                    }
                return View::make('stations.expired');
                break;
            case "application/ld+json":
                $stationStringName = \hyperRail\StationString::convertToString($station_id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                $data = file_get_contents($URL);
                    $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $station_id);
                    foreach ($newData['@graph'] as $graph){
                        if (strpos($graph['@id'],$liveboard_id) !== false) {
                            return $graph;
                        }
                    }
                    return View::make('stations.expired');
                break;
            default:
                    $stationStringName = \hyperRail\StationString::convertToString($station_id);
                    $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                    $data = file_get_contents($URL);
                    $newData = \hyperRail\iRailFormatConverter::convertLiveboardData($data, $station_id);
                    foreach ($newData['@graph'] as $graph){
                        if (strpos($graph['@id'],$liveboard_id) !== false) {
                            return View::make('stations.departuredetail')->with('station', $graph)->with('departureStation', $stationStringName);
                        }
                    }
                    return View::make('stations.expired');
                break;
        }
    }
}