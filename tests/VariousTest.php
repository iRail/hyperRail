<?php

// Start Session
session_start();

class VariousTest extends TestCase
{
    // Frontpage: Without session.
    public function testHomeWithoutSession()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
    }

    // Frontpage: With session.
    public function testHomeWithSession()
    {
        $this->withSession(['lang' => 'nl']);
        $this->visit('/');
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
