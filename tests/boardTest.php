<?php

class boardTest extends TestCase
{
    public function testBoardUrl()
    {
        $response = $this->call('GET', '/board');
        $this->assertEquals(301, $response->status());
    }
}