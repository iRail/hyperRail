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
        $response = $this->call('GET', '/stations/NMBS');
        $this->assertEquals(200, $response->status());
    }
}