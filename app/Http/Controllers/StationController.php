<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\App;
use irail\stations\Stations;
use ML\JsonLD\JsonLD;

class StationController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    /**
     * Uses irail\stations\Stations to resolve the station
     * @param string $query
     * @return object
     */
    private function getStations($query = "")
    {
        if ($query && $query !== "") {
            return Stations::getStations($query);
        } else {
            return Stations::getStations();
        }
    }

    /**
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        // Let the FormatNegotiator find out what to do with the request.
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = ['application/json', 'text/html', '*/*'];
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = "text/html";
        if (isset($result)) {
            $val = $result->getValue();
        }
        // Evaluate the preferred content type.
        switch ($val) {
            case "text/html":
                return View('stations.search');
                break;
            case "application/json":
            case "application/ld+json":
            default:
                return Response::make(json_encode($this->getStations(Input::get("q"))), 200)
                    ->header('Content-Type', 'application/ld+json')
                    ->header('Vary', 'accept');
                break;
        }
    }

    /**
     * Redirects to the stations page for NMBS
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToNMBSStations()
    {
        return Redirect::to('stations/NMBS');
    }

    /**
     * Shows a train or train data, based on the accept-header.
     * @param $station_id
     * @param $liveboard_id
     * @return array
     * @throws EasyRdf_Exception
     */
    public function specificTrain($station_id, $liveboard_id)
    {
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = ['application/json', 'text/html', '*/*'];
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();
        //get the right date-time to query
        $datetime = substr($liveboard_id, 0, 12);
        $datetime = strtotime($datetime);
        $archived = false;
        if ($datetime < strtotime("now")) {
            $archived = true;
        }
        switch ($val) {
            case "text/html":
                // Convert id to string for interpretation by old API

                $stationStringName = Stations::getStationFromId($station_id);

                if (! $archived) {
                    // Set up path to old api
                    $URL = "http://api.irail.be/liveboard/?station=" . urlencode($stationStringName->name) .
                        "&date=" . date("mmddyy", $datetime) . "&time=" . date("Hi", $datetime) .
                        "&fast=true&lang=nl&format=json";

                    // Get the contents.
                    $guzzleClient = new \GuzzleHttp\Client();
                    $guzzleRequest = $guzzleClient->get($URL);
                    $data = $guzzleRequest->getBody();

                    // Convert the data to the new liveboard object
                    $newData = \App\hyperRail\FormatConverter::convertLiveboardData($data, $station_id);
                    // Read new liveboard object and return the page but load data
                    foreach ($newData['@graph'] as $graph) {
                        if (strpos($graph['@id'], $liveboard_id) !== false) {
                            return View::make('stations.departuredetail')
                                ->with('station', $graph)
                                ->with('departureStation', $stationStringName);
                        }
                    }
                    App::abort(404);
                } else {
                    // If no match is found, attempt to look in the archive
                    // Fetch file using curl
                    $ch = curl_init(
                        "http://archive.irail.be/" . 'irail?subject=' .
                        urlencode('http://irail.be/stations/NMBS/' . $station_id . '/departures/' . $liveboard_id)
                    );
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $request_headers[] = 'Accept: text/turtle';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $turtle = (curl_exec($ch));
                    curl_close($ch);
                    // Convert turtle to json-ld
                    // Create a new graph
                    $graph = new EasyRdf_Graph();
                    if (empty($_REQUEST['data'])) {
                        // Load the sample information
                        $graph->parse($turtle, 'turtle');
                    }
                    // Export to JSON LD
                    $format = EasyRdf_Format::getFormat('jsonld');
                    $output = $graph->serialise($format);
                    if (!is_scalar($output)) {
                        $output = var_export($output, true);
                    }
                    // First, define the context
                    $context = [
                        "delay" => "http://semweb.mmlab.be/ns/rplod/delay",
                        "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
                        "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
                        "headsign" => "http://vocab.org/transit/terms/headsign",
                        "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
                        "stop" => [
                            "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                            "@type" => "@id"
                        ],
                        "seeAlso" => [
                            "@id" => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
                            "@type" => "@id",
                        ]
                    ];
                    // Next, encode the context as JSON
                    $jsonContext = json_encode($context);
                    // Compact the JsonLD by using @context
                    $compacted = JsonLD::compact($output, $jsonContext);
                    // Print the resulting JSON-LD!
                    $urlToFind = 'NMBS/' . $station_id . '/departures/' . $liveboard_id;
                    $stationDataFallback = json_decode(JsonLD::toString($compacted, true));
                    foreach ($stationDataFallback->{'@graph'} as $graph) {
                        if (strpos($graph->{'@id'}, $urlToFind) !== false) {
                            return View::make('stations.departurearchive')
                                ->with('station', $graph)
                                ->with('departureStation', $stationStringName);
                        }
                    }
                    App::abort(404);
                }
                break;
            case "application/json":
            case "application/ld+json":
            default:
                $stationStringName = Stations::getStationFromId($station_id);
                if (!$archived) {
                    $URL = "http://api.irail.be/liveboard/?station=" . urlencode($stationStringName->name) .
                        "&date=" . date("mmddyy", $datetime) . "&time=" . date("Hi", $datetime) .
                        "&fast=true&lang=nl&format=json";
                    $data = file_get_contents($URL);
                    $newData = \App\hyperRail\FormatConverter::convertLiveboardData($data, $station_id);
                    foreach ($newData['@graph'] as $graph) {
                        if (strpos($graph['@id'], $liveboard_id) !== false) {
                            $context = [
                                "delay" => "http://semweb.mmlab.be/ns/rplod/delay",
                                "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
                                "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
                                "headsign" => "http://vocab.org/transit/terms/headsign",
                                "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
                                "stop" => [
                                    "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                                    "@type" => "@id"
                                ],
                            ];
                            return ["@context" => $context, "@graph" => $graph];
                        }
                    }
                    App::abort(404);
                } else {
                    // If no match is found, attempt to look in the archive
                    // Fetch file using curl
                    $ch = curl_init("http://archive.irail.be/" . 'irail?subject=' .
                        urlencode('http://irail.be/stations/NMBS/' . $station_id . '/departures/' . $liveboard_id));
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    $request_headers[] = 'Accept: text/turtle';
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $turtle = (curl_exec($ch));
                    curl_close($ch);
                    // Convert turtle to json-ld
                    // Create a new graph
                    $graph = new EasyRdf_Graph();
                    if (empty($_REQUEST['data'])) {
                        // Load the sample information
                        $graph->parse($turtle, 'turtle');
                    }
                    // Export to JSON LD
                    $format = EasyRdf_Format::getFormat('jsonld');
                    $output = $graph->serialise($format);
                    if (!is_scalar($output)) {
                        $output = var_export($output, true);
                    }
                    // First, define the context
                    $context = [
                        "delay" => "http://semweb.mmlab.be/ns/rplod/delay",
                        "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
                        "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
                        "headsign" => "http://vocab.org/transit/terms/headsign",
                        "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
                        "stop" => [
                            "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                            "@type" => "@id"
                        ],
                        "seeAlso" => [
                            "@id" => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
                            "@type" => "@id",
                        ]
                    ];
                    // Next, encode the context as JSON
                    $jsonContext = json_encode($context);
                    // Compact the JsonLD by using @context
                    $compacted = JsonLD::compact($output, $jsonContext);
                    // Print the resulting JSON-LD!
                    $urlToFind = 'NMBS/' . $station_id . '/departures/' . $liveboard_id;
                    $stationDataFallback = json_decode(JsonLD::toString($compacted, true));
                    foreach ($stationDataFallback->{'@graph'} as $graph) {
                        if (strpos($graph->{'@id'}, $urlToFind) !== false) {
                            return ["@context" => $context, "@graph" => $graph];
                        }
                    }
                    App::abort(404);
                }
                break;
        }
    }

    /**
     * Shows a liveboard or liveboard data, based on the accept-header.
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function liveboard($id)
    {
        $guzzleClient = new \GuzzleHttp\Client();
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = ['application/json', 'text/html', '*/*'];
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = "text/html";
        //unless the negotiator has found something better for us
        if (isset($result)) {
            $val = $result->getValue();
        }
        switch ($val) {
            case "text/html":
                try {
                    $station = Stations::getStationFromId($id);
                    if ($station == null) {
                        throw new \App\Exceptions\StationConversionFailureException();
                    }
                    $data = ['station' => $station];
                    return Response::view('stations.liveboard', $data)
                        ->header('Content-Type', "text/html")
                        ->header('Vary', 'accept');
                    break;
                } catch (\App\Exceptions\StationConversionFailureException $ex) {
                    App::abort(404);
                }
                break;
            case "application/json":
            case "application/ld+json":
            default:
                try {
                    $stationStringName = Stations::getStationFromId($id);

                    if ($stationStringName == null) {
                        throw new \App\Exceptions\StationConversionFailureException();
                    }
                    //Check for optional time parameters
                    $datetime = Input::get("datetime");

                    if (isset($datetime) && strtotime($datetime)) {
                        $datetime = strtotime($datetime);
                    } else {
                        $datetime = strtotime("now");
                    }

                    $URL = "http://api.irail.be/liveboard/?station="
                        . $stationStringName->name . "&fast=true&lang=nl&format=json&date="
                        . date("mmddyy", $datetime) . "&time=" . date("Hi", $datetime);

                    $guzzleRequest = $guzzleClient->get($URL);
                    $data = $guzzleRequest->getBody();

                    try {
                        $newData = \App\hyperRail\FormatConverter::convertLiveboardData($data, $id);
                        $jsonLD = (string)json_encode($newData);
                        return Response::make($jsonLD, 200)
                            ->header('Content-Type', 'application/ld+json')
                            ->header('Vary', 'accept');
                    } catch (Exception $ex) {
                        $error = (string) json_encode(['error' => 'An error occured while parsing the data']);
                        return Response::make($error, 500)
                            ->header('Content-Type', 'application/json')
                            ->header('Vary', 'accept');
                    }
                } catch (\App\Exceptions\StationConversionFailureException $ex) {
                    $error = (string) json_encode(['error' => 'This station does not exist!']);
                    App::abort(404);
                }
                break;
        }
    }
}
