<?php

class StationController extends \BaseController {

	public function index(){
        return View::make('stations.search');
    }

    public function liveboard($id){
        // TODO: content delegation
        return $id;
    }

}