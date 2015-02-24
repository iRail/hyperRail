<?php

class LanguageController extends BaseController
{

    /*
    |--------------------------------------------------------------------------
    | Language controller
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return View::make('language');
    }
}