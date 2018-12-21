<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Support\Facades\Redirect;

class BluebikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('language');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Redirect::to('https://datapiloten.be/bluebike/availabilities.geojson', 302);
    }

    public static function getJSON()
    {
        return Storage::disk('local')->get('bluebike.geojson');
    }
}
