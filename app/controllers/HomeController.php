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
        return Redirect::to('route');
	}
}