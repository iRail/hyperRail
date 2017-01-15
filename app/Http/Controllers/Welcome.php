<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class Welcome extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    public function index()
    {
        if (Session::get('lang') == null || empty(Session::get('lang'))) {
            return View('language');
        } else {
            return Redirect::to('route');
        }
    }
}
