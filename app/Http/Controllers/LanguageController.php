<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
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
