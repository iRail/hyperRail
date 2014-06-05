<?php

use ML\JsonLD\JsonLD;

class RouteController extends \BaseController {
    public function index(){

        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = $result->getValue();

        switch ($val){
            case "text/html":
                return Response::view('route.planner')->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
            case "application/json":
                return Response::make($this::getJSON())->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
            default:
                return Response::view('route.planner')->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
        }
    }

    static function getJSON(){
        $from =  Input::get('from'); // required
        $to = Input::get('to'); // required
        $time = Input::get('time'); // optional, default to current time if null
        if (!Input::get('time')){
            $time = date("Hi", time());
        }
        $date = Input::get('date'); // optional, default to current date if null
        if (!Input::get('date')){
            $date = date("dmy", time());
        }
        $timeSel = Input::get('timeSel'); // optional, default to 'depart at hour' if null
        if (!Input::get('timeSel')){
            $timeSel = "depart";
        }
        $lang = Config::get('app.locale');
        $fromName = \hyperRail\StationString::convertToString($from)->name;
        $toName = \hyperRail\StationString::convertToString($to)->name;
        try{
            $json = file_get_contents('http://api.irail.be/connections/?to=' . $toName . '&from=' . $fromName . '&date=' . $date . '&time=' . $time . '&timeSel=' . $timeSel . '&lang=' . $lang . '&format=json');
            return trim($json);
        }
        catch(ErrorException $ex){
            return null;
        }
    }

}