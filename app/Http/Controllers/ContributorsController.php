<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
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
