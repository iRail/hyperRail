<?php


class BoardTest extends TestCase
{
    public function testBoardUrl()
    {
        $response = $this->call('GET', '/board');
        $this->assertEquals(301, $response->status());
    }

    /**
     * PHPUNIT: test for /board/{station}/{station2}.
     */
    public function testBoardUrlTwoStations()
    {
        $response = $this->call('GET', '/board/008821907/008821006');
        $this->assertEquals(200, $response->status());
    }
}
