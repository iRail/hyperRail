<?php

use ML\JsonLD\JsonLD;

class RouteController extends \BaseController {
    public function index(){

        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json','text/html', '*/*');

        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = "text/html";
        //unless the negotiator has found something better for us
        if (isset($result)) {
            $val = $result->getValue();
        }

        switch ($val){
            case "text/html":
                return Response::view('route.planner')->header('Content-Type', "text/html")->header('Vary', 'accept');
                break;
            case "application/json":
            default:
                return Response::make($this::getJSON())->header('Content-Type', "application/json")->header('Vary', 'accept');
                break;
        }
    }

    static function getJSON(){
        if(Input::get('from') && Input::get('to')) {
            $from =  urldecode(Input::get('from')); // required
            $to = urldecode(Input::get('to')); // required
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

            $fromId = str_replace("http://irail.be/stations/NMBS/","",$from);
            $toId = str_replace("http://irail.be/stations/NMBS/","",$to);

            try{
                $json = file_get_contents('http://api.irail.be/connections/?to=' . $toId . '&from=' . $fromId . '&date=' . $date . '&time=' . $time . '&timeSel=' . $timeSel . '&lang=' . $lang . '&format=json');
                return trim($json);
            }
            catch(ErrorException $ex){
                return array(
                    'connection' => array(),
                );
            }
        } else {
            // Show the HYDRA JSON-LD for doing a request to the right URI
            /** Structure this RDF as follows (early draft):
<https://irail.be/route>
    void:uriLookupEndpoint "https://irail.be/route{?from,to}";
    hydra:search _:route.
_:route hydra:template "https://irail.be/route{?from,to}";
    hydra:mapping _:from, _:to.
_:from hydra:variable "from";
    hydra:required true ;
    hydra:property <http://semweb.mmlab.be/ns/rplod/stop> .
_:to hydra:variable "to" ;
    hydra:required true ;
    hydra:property <http://semweb.mmlab.be/ns/rplod/stop> .

             */

        }
    }

}
