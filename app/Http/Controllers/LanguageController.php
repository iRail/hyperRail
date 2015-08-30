<?php

namespace App\Http\Controllers;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    public function index()
    {
        return View('language');
    }
}
