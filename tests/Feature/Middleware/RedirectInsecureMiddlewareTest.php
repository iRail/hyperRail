<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;

class RedirectInsecureMiddlewareTest extends TestCase
{
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
