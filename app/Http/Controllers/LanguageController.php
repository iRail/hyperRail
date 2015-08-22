<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;

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
