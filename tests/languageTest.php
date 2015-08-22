<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class languageTest extends TestCase
{
    public function testLanguage()
    {
        $response = $this->call('GET', '/language');
        $this->assertEquals(200, $response->status());
    }
}
