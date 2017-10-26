<?php

namespace App\Http\Controllers;

use Request;
use EasyRdf_Graph;
use EasyRdf_Format;
use ML\JsonLD\JsonLD;
use GuzzleHttp\Client;
use irail\stations\Stations;
use Negotiation\FormatNegotiator;
use App\hyperRail\FormatConvertor;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    /**
     * Shows a train or train data, based on the accept-header.
     *
     * @return array
     * @throws EasyRdf_Exception
     */
    public function vehicle($train_id)
    {
        $negotiator = new FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = ['application/json', 'text/html', '*/*'];
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();

        // Set up path to old api
        $URL = 'http://api.irail.be/vehicle/?id=' . $train_id .
            '&lang=nl&format=json';

        // Get the contents.
        $guzzleClient = new Client();
        $guzzleRequest = $guzzleClient->get($URL);
        $data = $guzzleRequest->getBody();
        $data = \GuzzleHttp\json_decode($data, true);

        // Read new liveboard object and return the page but load data

        switch ($val) {
            case 'text/html':
                return View('vehicle.index')
                    ->with('train_id', $train_id)
                    ->with('stops', $data['stops']['stop']);

            case 'application/json':
            case 'application/ld+json':
            default:
                $context = [
                    'delay' => 'http://semweb.mmlab.be/ns/rplod/delay',
                    'platform' => 'http://semweb.mmlab.be/ns/rplod/platform',
                    'scheduledDepartureTime' => 'http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime',
                    'headsign' => 'http://vocab.org/transit/terms/headsign',
                    'routeLabel' => 'http://semweb.mmlab.be/ns/rplod/routeLabel',
                    'stop' => [
                        '@id' => 'http://semweb.mmlab.be/ns/rplod/stop',
                        '@type' => '@id',
                    ],
                ];

                return ['@context' => $context, '@graph' => $data['stops']['stop']];
        }


        App::abort(404);
    }

}
