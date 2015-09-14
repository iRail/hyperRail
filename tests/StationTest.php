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

        $request = $guzzleClient->request('GET','http://127.0.0.1:8000/stations/nmbs');
        $OutputData = json_decode($request->getBody(true), true);

        $this->assertEquals(200, $request->getStatusCode());
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
