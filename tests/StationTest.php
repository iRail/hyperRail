<?php

use GuzzleHttp\Client;

class StationTest extends TestCase
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

    /**
     * TODO: Add assertHaskey() methods.
     */
    public function testStationNmbsCurlLdJson()
    {
        $guzzleClient = new Client([
            'headers' => [
                'Accept' => 'application/json']
        ]);
        
        $response = $this->call('GET','/stations/nmbs', [
            'headers' => [
                'Accept' => 'application/json']
        ]);
        
        $outputData = json_decode($response->getOriginalContent(), true);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSpecificStation()
    {
        $urlCapitalCasing = $this->call('GET', '/stations/NMBS/008727100');
        $urlLowerCasing = $this->call('GET', '/stations/nmbs/008727100');

        $this->assertEquals(200, $urlCapitalCasing->status());
        $this->assertEquals(200, $urlLowerCasing->status());
    }

    public function testSpecificTrainDepartures()
    {
        $urlCapitalCasing = $this->call('GET', '/stations/NMBS/008727100/departures');
        $urlLowerCasing = $this->call('GET', '/stations/nmbs/008727100/departures');

        $this->assertEquals(200, $urlCapitalCasing->status());
        $this->assertEquals(200, $urlLowerCasing->status());
    }
}
