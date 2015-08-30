<?php

namespace App\Http\Controllers;

class ContributorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    public function showContributors()
    {
        return View('contributors.home');
    }
}
