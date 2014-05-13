<?php

namespace hyperrail\Tests;

use ML\JsonLD\JsonLD;
use EasyRdf_Graph;
use EasyRdf_Format;

class JsonLDTest
{
    public static function doTest()
    {
        // Create a new graph
        $graph = new EasyRdf_Graph();
        if (empty($_REQUEST['data'])) {
            // Load the sample information
            $graph->load('http://' . _DOMAIN_ . '/NMBS.ttl', 'turtle');
        }
        // Export to JSON LD
        $format = EasyRdf_Format::getFormat('jsonld');
        $output = $graph->serialise($format);
        if (!is_scalar($output)) {
            $output = var_export($output, true);
        }
        // First, define the context
        $context = array(
            "arrivalTime"                  => "http://semweb.mmlab.be/ns/rplod/nextStopActualArrivalTime",
            "actualDepartureTime"          => "http://semweb.mmlab.be/ns/rplod/actualDepartureTime",
            "delay"                        => "http://semweb.mmlab.be/ns/rplod/delay",
            "nextStop"                     => "http://semweb.mmlab.be/ns/rplod/nextStop",
            "nextStopDelay"                => "http://semweb.mmlab.be/ns/rplod/nextStopDelay",
            "nextStopPlatform"             => "http://semweb.mmlab.be/ns/rplod/nextStopPlatform",
            "nextStopScheduledArrivalTime" => "http://semweb.mmlab.be/ns/rplod/nextStopScheduledArrivalTime",
            "platform"                     => "http://semweb.mmlab.be/ns/rplod/platform",
            "scheduledDepartureTime"       => "http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime",
            "headsign"                     => "http://vocab.org/transit/terms/headsign",
            "routeLabel"                   => "http://semweb.mmlab.be/ns/rplod/routeLabel",
            "stop"                         => "http://semweb.mmlab.be/ns/rplod/stop"
        );
        // Next, encode the context as JSON
        $jsonContext = json_encode($context);
        // Compact the JsonLD by using @context
        $compacted = JsonLD::compact($output, $jsonContext);
        // Print the resulting JSON-LD!
        print JsonLD::toString($compacted, true);
    }
} 