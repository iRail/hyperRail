<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class Welcome extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    public function index()
    {
        if (!Session::get('lang')) {
            return View('language');
        } else {
            return Redirect::to('route');
        }
    }
}
