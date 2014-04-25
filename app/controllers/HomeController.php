<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	*/

	public function showWelcome()
	{
        // Set up negotiation
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        echo "Server will return this format: " . $result->getValue();
        echo "\n";
        echo "Server has determined this quality: " . $result->getQuality();
	}

}