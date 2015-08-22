<?php


class variousTest extends TestCase
{
    public function testHome()
    {
        $response = $this->call('GET', '/');
        $this->assertEquals(200, $response->status());
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
