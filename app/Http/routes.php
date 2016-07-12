<?php

/**
 * --------------------------------------------------------------------------
 * Application Routes
 * --------------------------------------------------------------------------.
 *
 * Here is where you can register all of the routes for an application.
 * It's a breeze. Simply tell Laravel the URIs it should respond to
 * and give it the controller to call when that URI is requested.
 */
Route::get('/', 'Welcome@index');
Route::get('/route', 'RouteController@index');
Route::get('/language', 'LanguageController@index');
Route::get('/contributors', 'ContributorsController@showContributors');

Route::get('/stations/', 'StationController@redirectToNMBSStations');
Route::get('/stations/NMBS', 'StationController@index');
Route::get('/stations/NMBS/{id}', [
    'as' => 'station.index',
    'uses' => 'StationController@liveboard',
]);

Route::get('/stations/NMBS/{id}/departures', 'StationController@liveboard'); // should list the departures
Route::get('/stations/NMBS/{id}/departures/{trainHash}', 'StationController@specificTrain');

Route::get('/stations/nmbs', 'StationController@index'); // should list stations
Route::get('/stations/nmbs/{id}', 'StationController@liveboard'); // should list information about the station
Route::get('/stations/nmbs/{id}/departures', 'StationController@liveboard'); // should list the departures
Route::get('/stations/nmbs/{id}/departures/{trainHash}', [
    'as' => 'stations.departures.hash',
    'uses' => 'StationController@specificTrain',
]);

/*
 * --------------------------------------------------------------------------
 * Classic iRail redirection messages
 * --------------------------------------------------------------------------
 *
 */

// Classic: irail.be/route/Station/StationTwo/?time=timestamp&date=date&direction=depart
Route::get('/route/{departure_station}/{destination_station}/', 'ClassicRedirectController@redirectHomeRoute');
// Classic: irail.be/board
Route::get('/board', 'ClassicRedirectController@redirectBoard');
// Classic: irail.be/board/Station
Route::get('/board/{station}', 'ClassicRedirectController@redirectBoardSingleStation');
// Classic: irail.be/board/Station/StationTwo
Route::get('/board/{station}/{station2}', 'ClassicRedirectController@redirectBoardTwoStations');
