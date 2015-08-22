<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

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
