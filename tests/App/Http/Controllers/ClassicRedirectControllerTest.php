<?php

use App\Http\Controllers\ClassicRedirectController;

class ClassicRedirectControllerTest extends TestCase
{
    public function testRedirectSettings()
    {
        $controller = new ClassicRedirectController();
        $data = $controller->redirectSettings();
        $expected = 'iRail has been changed. iRail no longer has a dedicated settings page.';

        $this->assertEquals($data, $expected);

    }

    public function testRedirectHomeRedirectError()
    {
        $controller = new ClassicRedirectController();
        $data = $controller->redirectHomeRoute(
            'http://irail.be/stations/NMBS/008039904',
            'http://irail.be/stations/NMBS/008032572'
        );

        $expected = "It looks like we couldn't convert your route request to the new format :(";

        $this->assertEquals($data, $expected);
    }

}