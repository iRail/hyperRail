<?php

use ML\JsonLD\JsonLD;
use hyperRail\Tests\JsonLDTest;

class ApiController extends \BaseController {

	public function test(){
        JsonLDTest::doTest();
    }
}