<?php

class stationTest extends TestCase
{
    public function testStation()
    {
        $response = $this->call('GET', '/stations');
        $this->assertEquals(302, $response->status());
        $this->assertRedirectedTo('/stations/NMBS');
    }

    public function testStationNmbs()
    {
        $responseNMBS = $this->call('GET', '/stations/NMBS');
        $responsenmbs = $this->call('GET', '/stations/nmbs');

        $this->assertEquals(200, $responseNMBS->status());
        $this->assertEquals(200, $responsenmbs->status());
    }
}