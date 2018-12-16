<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Support\Facades\Response;

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
        return Response::make($this::getJSON())
            ->header('Content-Type', 'application/json')
            ->header('Cache-Control', 'max-age=600');
    }

    public static function getJSON()
    {
        return Storage::disk('local')->get('bluebike.geojson');
    }
}
