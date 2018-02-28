<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Request;
use Negotiation\FormatNegotiator;
use Illuminate\Support\Facades\App;

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
    public function vehicle($train_id, $date = null)
    {
        if (starts_with($train_id, "http")) {
            $train_id = 'BE.NMBS.' . base_path($train_id);
        } elseif (!starts_with($train_id, "BE.NMBS.")) {
            $train_id = 'BE.NMBS.' . $train_id;
        }

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

        switch ($val) {
            case 'text/html':
                return View('vehicle.index')
                    ->with('train_id', substr($data['vehicle'],8))
                    ->with('stops', $data['stops']['stop']);

            case 'application/json':
            case 'application/ld+json':
            default:
                $context = [
                    'delay'                  => 'http://semweb.mmlab.be/ns/rplod/delay',
                    'platform'               => 'http://semweb.mmlab.be/ns/rplod/platform',
                    'scheduledDepartureTime' => 'http://semweb.mmlab.be/ns/rplod/scheduledDepartureTime',
                    'headsign'               => 'http://vocab.org/transit/terms/headsign',
                    'routeLabel'             => 'http://semweb.mmlab.be/ns/rplod/routeLabel',
                    'stop'                   => [
                        '@id'   => 'http://semweb.mmlab.be/ns/rplod/stop',
                        '@type' => '@id',
                    ],
                ];

                return ['@context' => $context, '@graph' => $data['stops']['stop']];
        }

        App::abort(404);
    }
}
