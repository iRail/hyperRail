<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    public function getIcs()
    {
        $trip = Input::get('trip');

        // Basic input check
        if (!isset($trip['departure']) || !isset($trip['arrival'])) {
            throw new CException('Incorrect trip data');
        }

        // Init ics file
        $vCalendar = new \Eluceo\iCal\Component\Calendar('www.irail.be');

        // Initiate first event and add departure information
        $vEvent = new \Eluceo\iCal\Component\Event();
        $vEvent->setDtStart(new \DateTime('@'.$trip['departure']['time']));
        $vEvent->setLocation('Station '.$trip['departure']['station']);
        $vEvent->setGeoLocation(new \Eluceo\iCal\Property\Event\Geo(
            $trip['departure']['stationinfo']['locationY'], 
            $trip['departure']['stationinfo']['locationX']
        ));
        $from = trans('export.stationSummary', [
            'station' => $trip['departure']['station'],
            'platform' => $trip['departure']['platform']
        ]);

        // Handle vias
        if (isset($trip['vias']) && $trip['vias']['number'] > 0) {
            foreach ($trip['vias']['via'] as $via) {
                // Set arrival time for previous event and save
                $vEvent->setDtEnd(new \DateTime('@'.$via['arrival']['time']));
                $vEvent->setSummary(trans('export.summary', [
                    'from' => $from,
                    'to' => trans('export.stationSummary', [
                        'station' => $via['station'],
                        'platform' => $via['departure']['platform']
                    ])
                ]));
                $vCalendar->addComponent($vEvent);

                // Initiate new event and set relevant fields
                $vEvent = new \Eluceo\iCal\Component\Event();
                $vEvent->setDtStart(new \DateTime('@'.$via['departure']['time']));
                $vEvent->setLocation('Station '.$via['station']);
                $vEvent->setGeoLocation(new \Eluceo\iCal\Property\Event\Geo(
                    $via['stationinfo']['locationY'], 
                    $via['stationinfo']['locationX']
                ));
                $from = trans('export.stationSummary', [
                    'station' => ['station'],
                    'platform' => $trip['departure']['platform']
                ]);
            }
        }

        // Close final event
        $vEvent->setDtEnd(new \DateTime('@'.$trip['arrival']['time']));
        $vEvent->setSummary(trans('export.summary', [
            'from' => $from,
            'to' => trans('export.stationSummary', [
                'station' => $trip['arrival']['station'],
                'platform' => $trip['arrival']['platform']
            ])
        ]));
        $vCalendar->addComponent($vEvent);

        // Render ICS for use in Angular
        echo $vCalendar->render();
    }
}
