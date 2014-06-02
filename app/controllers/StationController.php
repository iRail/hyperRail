<?php

class StationController extends \BaseController {

	public function index(){
        // TODO: content delegation
        return View::make('stations.search');
    }

    public function liveboard($id){

        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = $result->getValue();

        switch ($val){
            case "text/html":
                return Response::view('stations.liveboard')->header('Content-Type', "text/html");
                break;
            case "application/json":
                $stationStringName = \hyperRail\StationString::convertToString($id);
                $URL = "http://api.irail.be/liveboard/?station=" . $stationStringName->name . "&lang=nl&format=json";
                return $data = file_get_contents($URL);
                break;
            default:
                return Response::view('stations.liveboard')->header('Content-Type', "text/html");
                break;
        }
    }

}