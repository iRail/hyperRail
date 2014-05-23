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
            case "application/ld+json":
                // TODO: return JSON-LD
                break;
            case "application/json":
                $from =  Input::get('from'); // required
                $to = Input::get('to'); // required
                $time = Input::get('time'); // optional, default to current time if null
                $date = Input::get('date'); // optional, default to current date if null
                $timeSel = Input::get('timeSel');
                $lang = "nl"; // for this version, default to nl station names
                $fromName = \hyperRail\StationString::convertToString($from)->name;
                $toName = \hyperRail\StationString::convertToString($to)->name;
                try{
                    $json = file_get_contents('http://api.irail.be/connections/?to=' . $toName . '&from=' . $fromName . '&date=' . $date . '&time=' . $time . '&timeSel=' . $timeSel . '&lang=NL&format=json');
                    return $json;
                }
                catch(ErrorException $ex){
                    return "We could not retrieve data. Ensure that you have provided all required parameters: to, from, date, time, timeSel.";
                }
                break;
            default:
                return View::make('route.planner');
                break;
        }


    }

}