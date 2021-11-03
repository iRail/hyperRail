<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Use content negotiation to determine the correct format to return
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        // Set priorities to json (if the app requests json provide it in favor of html)
        $priorities = ['application/json', 'text/html', '*/*'];
        // Get the best appropriate content type
        $result = $negotiator->getBest($acceptHeader, $priorities);
        // Default to html if $result is not set
        $val = 'text/html';
        if (isset($result)) {
            $val = $result->getValue();
        }
        // See what kind of content we should return
        switch ($val) {
            case 'text/html':
                // In case we want to return html, just let
                // Laravel render the view and send the headers
                // This is a static page, where data is retrieved using javascript. Cache the static part for a long time.
                return Response::view('route.planner')
                    ->header('Content-Type', 'text/html')
                    ->header('Vary', 'accept')
                    ->header('Expires', (new Carbon())->addHours(24)->toAtomString())
                    ->header('Cache-Control', 'max-age=86400, s-maxage=43200');
                break;
            case 'application/json':
            default:
                // In case we want to return JSON(LD) or something else, generate
                // our JSON by calling our static function 'getJSON()'
                return Response::make($this::getJSON($request))
                    ->header('Content-Type', 'application/json')
                    ->header('Vary', 'accept')
                    ->header('Expires', (new Carbon())->addSeconds(30)->toAtomString())
                    ->header('Cache-Control', 'max-age=30');
                break;
        }
    }

    /**
     * Generate JSON based on data we get back from the iRail scraper.
     * @return array|string
     */
    public static function getJSON($request)
    {
        if ($request->input('from') && $request->input('to')) {
            // Most of our parsing will be done by checking various parameters,
            // most of them GET parameters.
            // Required parameters (from and to; destination and departure)
            $from = urldecode($request->input('from'));
            $to = urldecode($request->input('to'));
            // Optional time
            $time = $request->input('time');
            // If time is not set, default to now
            if (!$request->input('time')) {
                $time = date('Hi', time());
            }
            // Optional date
            $date = $request->input('date');
            // If date is not set, default to today
            if (!$request->input('date')) {
                $date = date('dmy', time());
            }
            // Time selector: does the user want to arrive or depart at this hour?
            // Optional. Default to 'depart at hour' if null.
            $timeSel = $request->input('timeSel');
            if (!$request->input('timeSel')) {
                $timeSel = 'depart';
            }
            // Get the app's current language/locale
            $lang = Config::get('app.locale');
            $fromId = str_replace('http://irail.be/stations/NMBS/', '', $from);
            $toId = str_replace('http://irail.be/stations/NMBS/', '', $to);
            try {
                $json = file_get_contents('http://api.irail.be/connections.php?to='
                    . $toId . '&from=' . $fromId . '&date=' . $date . '&time=' .
                    $time . '&timeSel=' . $timeSel . '&lang=' . $lang . '&format=json');

                return trim($json);
            } catch (ErrorException $ex) {
                return [
                    'connection' => [],
                ];
            }
        } else {
            // TODO: Show the HYDRA JSON-LD for doing a request to the right URI
            return 'Required parameters are missing.';
        }
    }
}
