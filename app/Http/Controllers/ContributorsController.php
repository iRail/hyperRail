<?php

namespace app\Http\Controllers;

use App\Http\Controllers\Controller;

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
