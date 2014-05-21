<?php

class RouteController extends \BaseController {

    public function index(){

        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = $result->getValue();

        switch ($val){
            case "text/html":
                return View::make('route.planner');
                break;
            case "application/json":
                // TODO: fetch appropriate JSON from old api
                return Input::all();
                break;
            default:
                return View::make('route.planner');
                break;
        }


    }

}