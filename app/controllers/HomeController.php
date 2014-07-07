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
        if (!Session::get('lang')){
            return View::make('language');
        }else{
            return Redirect::to('route');
        }
	}
}