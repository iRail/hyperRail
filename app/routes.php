<?php

/*
|--------------------------------------------------------------------------
| New hypermedia powered routes
|--------------------------------------------------------------------------
|
*/

App::missing(function($exception)
{
    return Response::view('errors.404', array(), 404);
});

Route::get('/', 'HomeController@showWelcome');
Route::get('/route', 'RouteController@index');

Route::get('/language', 'LanguageController@index');

Route::get('/stations/', 'StationController@redirectToNMBSStations');
Route::get('/stations/NMBS', 'StationController@index');
Route::get('/stations/NMBS/{id}', 'StationController@liveboard');
Route::get('/stations/NMBS/{id}/departures/{trainHash}', 'StationController@specificTrain');

Route::get('/stations/nmbs', 'StationController@index');
Route::get('/stations/nmbs/{id}', 'StationController@liveboard');
Route::get('/stations/nmbs/{id}/departures/{trainHash}', 'StationController@specificTrain');

/*
|--------------------------------------------------------------------------
| Classic iRail redirection messages
|--------------------------------------------------------------------------
|
*/

// Classic: irail.be/route/Station/StationTwo/?time=timestamp&date=date&direction=depart
Route::get('/route/{departure_station}/{destination_station}/', 'ClassicRedirectController@redirectHomeRoute');
// Classic: irail.be/board
Route::get('/board', 'ClassicRedirectController@redirectBoard');
// Classic: irail.be/board/Station
Route::get('/board/{station}', 'ClassicRedirectController@redirectBoardSingleStation');
// Classic: irail.be/board/Station/StationTwo
Route::get('/board/{station}/{station2}', 'ClassicRedirectController@redirectBoardTwoStations');
// Classic: irail.be/settings
// Route::get('/settings', 'ClassicRedirectController@redirectSettings');
