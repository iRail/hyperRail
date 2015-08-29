<?php

class RedirectInsecureMiddlewareTest extends TestCase
{
    protected function refreshApplication()
    {
        // Overwrite method. Env will be set with setEnvironment
    }

    public function testProductionEnvRedirectToSecure()
    {
        $this->setEnvironment('production');
        $response = $this->call('GET', '/route');

        $this->assertEquals(301, $response->status());
    }

    public function testLocalEnvDoesNotRedirectToSecure()
    {
        $this->setEnvironment('local');
        $response = $this->call('GET', '/route');

        $this->assertEquals(200, $response->status());
    }

    private function setEnvironment($env)
    {
        putenv('APP_ENV='.$env);

        $this->app = $this->createApplication();
    }
}
