<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class boardTest extends TestCase
{
    public function testBoardUrl()
    {
        $response = $this->call('GET', '/board');
        $this->assertEquals(200, $response->status());
    }
}