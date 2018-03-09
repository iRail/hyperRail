<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;

class LanguageMiddlewareTest extends TestCase
{
    public function testDefaultLanguage()
    {
        $this->setAssertLanguageTest('en');
    }

    public function testSetLanguageToDutch()
    {
        $this->setAssertLanguageTest('nl', 'nl');
    }

    public function testSetLanguageToFrench()
    {
        $this->setAssertLanguageTest('fr', 'fr');
    }

    public function testSetLanguageToFrenchWhileBrowserDutch()
    {
        // GET parameters should have priority over browser language.
        $response = $this->get('/?lang=fr', ['accept-language' => 'nl-be']);
        $this->assertEquals('fr', $this->getApplicationLanguage());
    }

    public function testSetLanguageFromBrowser()
    {
        $response = $this->get('/', ['accept-language' => 'nl-be']);
        $this->assertEquals('nl', $this->getApplicationLanguage());
    }

    public function testSetLanguageFromBrowser2()
    {
        $response = $this->get('/', ['accept-language' => 'nl']);
        $this->assertEquals('nl', $this->getApplicationLanguage());
    }

    public function testSetLanguageFromBrowser3()
    {
        // unsupported languages mixed with supported. Should pick first supported.
        $response = $this->get('/', ['accept-language' => 'da, nl;q=0.9, en;q=0.5']);
        $this->assertEquals('nl', $this->getApplicationLanguage());
    }

    public function testSetLanguageFromBrowser4()
    {
        // unsupported languages. Should fall back to en.
        $response = $this->get('/', ['accept-language' => 'da, se;q=0.9, no;q=0.5']);
        $this->assertEquals('en', $this->getApplicationLanguage());
    }

    public function testFallbackToDefaultLanguageWhenLanguageDoesNotExist()
    {
        $this->setAssertLanguageTest('en', 'NotALanguage');
    }

    private function setAssertLanguageTest($expected, $lang = null)
    {
        $params = [];
        if (null !== $lang) {
            $params['lang'] = $lang;
        }

        $response = $this->action('GET', 'RouteController@index', [], $params);

        $this->assertEquals($expected, $this->getApplicationLanguage());
    }

    private function getApplicationLanguage()
    {
        return app()->getLocale();
    }
}
