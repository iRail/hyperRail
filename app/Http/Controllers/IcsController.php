<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;

class IcsController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    public function getIcs()
    {
        $trip = Input::get('trip');

        $vCalendar = new \Eluceo\iCal\Component\Calendar('www.irail.be');

        // Initiate first event and add departure information
        $vEvent = new \Eluceo\iCal\Component\Event();
        $vEvent->setDtStart(new \DateTime('@'.$trip['departure']['time']));
        $from = $trip['departure']['station'].' ('.$trip['departure']['platform'].')';

        // Handle vias
        foreach ($trip['vias']['via'] as $via) {
            // Set arrival time for previous event and save
            $vEvent->setDtEnd(new \DateTime('@'.$via['arrival']['time']));
            $vEvent->setSummary('iRail - '.$from.' to '.$via['station'].' ('.$via['departure']['platform'].')');
            $vCalendar->addComponent($vEvent);

            // Initiate new event and set relevant fields
            $vEvent = new \Eluceo\iCal\Component\Event();
            $vEvent->setDtStart(new \DateTime('@'.$via['departure']['time']));
            $from = $via['station'].'('.$via['departure']['platform'].')';
        }

        // Close final event
        $vEvent->setDtEnd(new \DateTime('@'.$trip['arrival']['time']));
        $vEvent->setSummary('iRail - '.$from.' to '.$trip['arrival']['station'].' ('.$trip['arrival']['platform'].')');
        $vCalendar->addComponent($vEvent);

        // Render ICS for use in Angular
        echo $vCalendar->render();
    }
}
