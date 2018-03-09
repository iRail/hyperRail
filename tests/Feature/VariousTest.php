<?php

namespace Tests\Feature;

use Tests\TestCase;

class VariousTest extends TestCase
{
    public function testHome()
    {
        // expect redirect, since call includes "accept-language" header
        $response = $this->call('GET', '/');
        $this->assertEquals(302, $response->status());
    }

    public function testContributors()
    {
        $response = $this->call('GET', '/contributors');
        $this->assertEquals(200, $response->status());
    }

    public function testLanguage()
    {
        $response = $this->call('GET', '/language');
        $this->assertEquals(200, $response->status());
    }
}
