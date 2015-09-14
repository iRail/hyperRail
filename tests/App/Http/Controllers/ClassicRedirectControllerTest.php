<?php

use App\Http\Controllers\ClassicRedirectController;

class ClassicRedirectControllerTest extends TestCase
{
    public function testRedirectSettings()
    {
        $controller = new ClassicRedirectController();
        $data = $controller->redirectSettings();
        $expected = 'iRail has been changed. iRail no longer has a dedicated settings page.';

        $this->assertEquals($data, $expected);

    }

}