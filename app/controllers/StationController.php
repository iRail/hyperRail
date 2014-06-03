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
                return Response::view('stations.liveboard')->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
            case "application/ld+json":
                $stationStringName = \hyperRail\StationString::convertToString($id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                $data = file_get_contents($URL);
                \hyperRail\iRailFormatConverter::convertLiveboardData($data, $id);
                $response = Response::make($data, 200);
                $response->header('Content-Type', 'application/ld+json');
                $response->header('Vary', 'accept');
                break;
            case "application/json":
                $stationStringName = \hyperRail\StationString::convertToString($id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&fast=true&lang=nl&format=json";
                $data = file_get_contents($URL);
                $response = Response::make($data, 200);
                $response->header('Content-Type', 'application/json');
                $response->header('Vary', 'accept');
                return $response;
                break;
            default:
                return Response::view('stations.liveboard')->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
        }
    }
}