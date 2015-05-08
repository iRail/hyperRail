<?php

use ML\JsonLD\JsonLD;

class StationController extends \BaseController
{
    /**
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        // Let the FormatNegotiator find out what to do with the request.

        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json', 'text/html', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = "text/html";
        if (isset($result)) {
            $val = $result->getValue();
        }

        // Evaluate the preferred content type.

        switch ($val) {
            case "text/html":
                return View::make('stations.search');
                break;
            case "application/json":
            case "application/ld+json":
            default:
                return Response::make($this->getStations(Input::get("q")), 200)
                    ->header('Content-Type', 'application/ld+json')
                    ->header('Vary', 'accept');
                break;
        }
    }

    // TODO: rewrite using an in-memory store (e.g. redis)
    /**
     * @param string $query
     * @return string
     */
    private function getStations($query = "")
    {
        if ($query && $query !== "") {
            // Filter the stations on name match
            $stations = json_decode(File::get(app_path() . '/stations.json'));

            $newstations = new \stdClass;
            $newstations->{"@id"} = $stations->{"@id"};
            $newstations->{"@context"} = $stations->{"@context"};
            $newstations->{"@graph"} = array();
            
            // st. is the same as Saint
            $query = preg_replace("/st(\s|$)/i", "(saint|st|sint) ", $query);

            //make sure that we're only taking the first part before a /
            $query = explode("/", $query);
            $query = trim($query[0]);

            // Dashes are the same as spaces
            $query = $this->normalizeAccents($query);
            $query = str_replace("\-", "[\- ]", $query);
            $query = str_replace(" ", "[\- ]", $query);
            
            $count = 0;            

            foreach ($stations->{"@graph"} as $station) {
                if (preg_match('/.*' . $query . '.*/i', $this->normalizeAccents($station->{"name"}), $match)) {
                    $newstations->{"@graph"}[] = $station;
                    $count++;
                } elseif (isset($station->alternative)) {
                    if (is_array($station->alternative)) {
                        foreach ($station->alternative as $alternative) {
                            if (preg_match('/.*(' . $query . ').*/i', $this
                                ->normalizeAccents($alternative->{"@value"}), $match)) {
                                $newstations->{"@graph"}[] = $station;
                                $count++;
                                break;
                            }
                        }
                    } else {
                        if (preg_match('/.*' . $query . '.*/i', $this
                            ->normalizeAccents($station->alternative->{"@value"}))) {
                            $newstations->{"@graph"}[] = $station;
                            $count++;
                        }
                    }
                }
                if ($count > 5) {
                    return json_encode($newstations);
                }
            }
            return json_encode($newstations);
        } else {
            return File::get(app_path() . '/stations.json');
        }
    }

    /**
     * @param $str
     * @return string
     * Languages supported are: German, French and Dutch
     * We have to take into account that some words may have accents
     * Taken from https://stackoverflow.com/questions/3371697/replacing-accented-characters-php
     */
    public function normalizeAccents($str)
    {
        $unwanted_array = array(
            'Š' => 'S', 'š' => 's', 'Ž' => 'Z', 'ž' => 'z',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A',
            'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E',
            'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
            'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y',
            'Þ' => 'B', 'ß' => 'Ss', 'à' => 'a', 'á' => 'a',
            'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a',
            'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i',
            'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o',
            'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u',
            'û' => 'u', 'ý' => 'y', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y'
        );
        
        return strtr($str, $unwanted_array);
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
     * Shows a liveboard or liveboard data, based on the accept-header.
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function liveboard($id)
    {
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json', 'text/html', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = "text/html";
        //unless the negotiator has found something better for us
        if (isset($result)) {
            $val = $result->getValue();
        }

        switch ($val) {
            case "text/html":
                try {
                    $station = \hyperRail\StationString::convertToString($id);
                    if ($station == null) {
                        throw new StationConversionFailureException();
                    }
                    $data = array('station' => $station);
                    return Response::view('stations.liveboard', $data)
                        ->header('Content-Type', "text/html")
                        ->header('Vary', 'accept');
                    break;
                } catch (StationConversionFailureException $ex) {
                    App::abort(404);
                }
                break;
            case "application/json":
            case "application/ld+json":
            default:
                try {
                    $stationStringName = \hyperRail\StationString::convertToString($id);
                    if ($stationStringName == null) {
                        throw new StationConversionFailureException();
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
                    $data = file_get_contents($URL);

                    try {
                        $newData = \hyperRail\FormatConverter::convertLiveboardData($data, $id);
                        $jsonLD = (string)json_encode($newData);
                        return Response::make($jsonLD, 200)
                            ->header('Content-Type', 'application/ld+json')
                            ->header('Vary', 'accept');
                    } catch (Exception $ex) {
                        $error = (string)json_encode(array('error' => 'An error occured while parsing the data'));
                        return Response::make($error, 500)
                            ->header('Content-Type', 'application/json')
                            ->header('Vary', 'accept');
                    }

                } catch (StationConversionFailureException $ex) {
                    $error = (string)json_encode(array('error' => 'This station does not exist!'));
                    App::abort(404);
                }
                break;
        }
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
        $priorities = array('application/json', 'text/html', '*/*');
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
                $stationStringName = \hyperRail\StationString::convertToString($station_id);

                if (!$archived) {
                    // Set up path to old api
                    $URL = "http://api.irail.be/liveboard/?station=" . urlencode($stationStringName->name) .
                        "&date=" . date("mmddyy", $datetime) . "&time=" . date("Hi", $datetime) .
                        "&fast=true&lang=nl&format=json";

                    // Get the contents of this path
                    $data = file_get_contents($URL);

                    // Convert the data to the new liveboard object
                    $newData = \hyperRail\FormatConverter::convertLiveboardData($data, $station_id);
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
                    $context = array(
                        "delay" => "http://semweb.mmlab.be/ns/rplod/delay",
                        "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
                        "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
                        "headsign" => "http://vocab.org/transit/terms/headsign",
                        "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
                        "stop" => array(
                            "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                            "@type" => "@id"
                        ),
                        "seeAlso" => array(
                            "@id" => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
                            "@type" => "@id",
                        )
                    );
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
                $stationStringName = \hyperRail\StationString::convertToString($station_id);
                if (!$archived) {
                    $URL = "http://api.irail.be/liveboard/?station=" . urlencode($stationStringName->name) .
                        "&date=" . date("mmddyy", $datetime) . "&time=" . date("Hi", $datetime) .
                        "&fast=true&lang=nl&format=json";
                    $data = file_get_contents($URL);
                    $newData = \hyperRail\FormatConverter::convertLiveboardData($data, $station_id);
                    foreach ($newData['@graph'] as $graph) {
                        if (strpos($graph['@id'], $liveboard_id) !== false) {
                            $context = array(
                                "delay" => "http://semweb.mmlab.be/ns/rplod/delay",
                                "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
                                "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
                                "headsign" => "http://vocab.org/transit/terms/headsign",
                                "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
                                "stop" => array(
                                    "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                                    "@type" => "@id"
                                ),
                            );
                            return array("@context" => $context, "@graph" => $graph);
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
                    $context = array(
                        "delay" => "http://semweb.mmlab.be/ns/rplod/delay",
                        "platform" => "http://semweb.mmlab.be/ns/rplod/platform",
                        "scheduledDepartureTime" => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
                        "headsign" => "http://vocab.org/transit/terms/headsign",
                        "routeLabel" => "http://semweb.mmlab.be/ns/rplod/routeLabel",
                        "stop" => array(
                            "@id" => "http://semweb.mmlab.be/ns/rplod/stop",
                            "@type" => "@id"
                        ),
                        "seeAlso" => array(
                            "@id" => "http://www.w3.org/2000/01/rdf-schema#seeAlso",
                            "@type" => "@id",
                        )
                    );
                    // Next, encode the context as JSON
                    $jsonContext = json_encode($context);
                    // Compact the JsonLD by using @context
                    $compacted = JsonLD::compact($output, $jsonContext);
                    // Print the resulting JSON-LD!
                    $urlToFind = 'NMBS/' . $station_id . '/departures/' . $liveboard_id;
                    $stationDataFallback = json_decode(JsonLD::toString($compacted, true));
                    foreach ($stationDataFallback->{'@graph'} as $graph) {
                        if (strpos($graph->{'@id'}, $urlToFind) !== false) {
                            return array("@context" => $context, "@graph" => $graph);
                        }
                    }
                    App::abort(404);
                }
                break;
        }
    }
}
