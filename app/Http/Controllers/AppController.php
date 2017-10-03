<?php

namespace App\Http\Controllers;

class AppController extends Controller
{

    public function __construct()
    {
        $this->middleware('language');
    }

    /**
     * Display the spitsgids page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return View('app');
    }
}
