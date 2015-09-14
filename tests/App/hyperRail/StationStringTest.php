<?php

use hyperRail\StationString;

class stationStringTest extends TestCase
{
    public function testConvertToIdCorrect()
    {
        $controller = new StationString();
        $method = $controller->convertToId('Frankfurt am Main Hbf');

        $this->assertTrue(sizeof($method) > 0);
    }

    public function testConvertToIdFalse()
    {
        $controller = new StationString();
        $method = $controller->convertToId('foo');

        $this->assertFalse(sizeof($method) < 0);
        $this->assertNull($method);
    }

}