<?php

class StationController extends \BaseController {

	public function index(){
        return View::make('stations.search');
    }

}