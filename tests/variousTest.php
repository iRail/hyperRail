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

    /**
     * English hyperlinks testing.
     * todo set tests for hyperlinks in navigation and body
     */
    public function testContributoonPageHyperlinksEN()
    {
        $url = $this->visit('/contributors?lang=en');

        // Navbar routes.
        $url->click('Plan new route')->seePageIs('/route');
        $url->click('Search stations')->seePageIs('/stations/NMBS');

        // Hyperlinks footer
        $url->click('Contributors')->seePageIs('/contributors');
        $url->click('Language')->seePageIs('/language');
    }

    /**
     * Dutch hyperlinks testing.
     * todo set tests for hyperlinks in navigation and body
     */
    public function testContributorsPageHyperlinksNL()
    {
        $url = $this->visit('/contributors?lang=nl');

        // Hyperlinks footer
        $url->click('Bijdragers')->seePageIs('/contributors');
        $url->click('Taalkeuze')->seePageIs('/language');
    }

    public function testLanguage()
    {
        $response = $this->call('GET', '/language');
        $this->assertEquals(200, $response->status());
    }
}