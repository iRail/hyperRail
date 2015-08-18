<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\URL;
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
        if (! Session::get('lang')) {
            return View('language');
        } else {
            return Redirect::to('route');
        }
    }
}
