<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class stationTest extends TestCase
{
    public function testStation()
    {
        $response = $this->call('GET', '/stations');
        $this->assertEquals(302, $response->status());
    }

    public function testStationNmbs()
    {
        $responseNMBS = $this->call('GET', '/stations/NMBS');
        $responsenmbs = $this->call('GET', '/stations/nmbs');

        $this->assertEquals(200, $responseNMBS->status());
        $this->assertEquals(200, $responsenmbs->status());
    }

    public function testSpecificStation()
    {
        $response = $this->call('GET', '/stations/NMBS/008727100');
        $this->assertEquals(200, $response->status());
    }
}