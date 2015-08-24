<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

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
