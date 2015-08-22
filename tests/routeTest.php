<?php

class nmbsTest extends TestCase
{
    public function testRoute()
    {
        $defaultURL = $this->call('GET', '/route');
        $dutchURL   = $this->call('GET', '/route?lang=nl');
        $frenchURL  = $this->call('GET', '/route?lang=fr');
        $englishURL = $this->call('GET', '/route?lang=en');

        $this->assertEquals(200, $defaultURL->status());
        $this->assertEquals(200, $dutchURL->status());
        $this->assertEquals(200, $frenchURL->status());
        $this->assertEquals(200, $englishURL->status());
    }

    public function testDepartureAndArrivalRoute()
    {
        $response = $this->call('GET', '/route?to=http%3A%2F%2Firail.be%2Fstations%2FNMBS%2F008813003&from=http%3A%2F%2Firail.be%2Fstations%2FNMBS%2F008821907&date=180815&time=1128&timeSel=depart');
        $this->assertEquals(200, $response->status());
    }
}