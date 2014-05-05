<?php

use hyperRail\Tests\JsonLDTest;

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	*/

	public function showWelcome()
	{
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);

        $val = $result->getValue();

        switch ($val){
            case "text/html":
                return View::make('hello');
                break;
            case "application/json":
                JsonLDTest::doTest();
                break;
            default:
                return View::make('hello');
                break;
        }
	}

}