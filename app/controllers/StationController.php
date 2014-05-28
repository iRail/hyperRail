<?php

class StationController extends \BaseController {

	public function index(){
        return View::make('stations.search');
    }

    public function liveboard($id){

        $stationStringName = \hyperRail\StationString::convertToString($id);

        // TODO: fetch data from URL — currently the API is broken :(

        return View::make('stations.liveboard');
    }

}