<?php

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
