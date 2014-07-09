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

Route::filter('after', function($response)
{
// No caching for pages
$response->header("Pragma", "no-cache");
$response->header("Cache-Control", "no-store, no-cache, must-revalidate, max-age=0");
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

// OAuth via external provider
Route::get('/oauth/{provider}', 'OAuthLoginController@getLogin');

// OAuth server iRail
//Route::post('/token', 'TokenController@postToken');
//Route::get('/token', 'TokenController@postToken');
Route::get('/resource', 'ResourceController@getResource');
Route::get('/authorize', 'AuthorizeController@getAuthorize');
Route::post('/authorize', 'AuthorizeController@postAuthorize');

// iRail login-functionality
Route::get('/register', 'IRailLoginController@getRegister');
Route::get('/login', 'IRailLoginController@getLogin');

Route::post('/login', 'IRailLoginController@postLogin');
Route::post('/register', 'IRailLoginController@postRegister');

Route::group(array('before' => 'auth'), function(){
	Route::get('/logout', 'IRailLoginController@logout');
});

// Social media-providers API (currently only Twitter)
Route::get('/{provider}/{id}/friends', 'OAuthResourceController@getFriends');

// Adding a check in
Route::get('checkin/{departure}', 'CheckinController@store');
Route::get('checkout/{departure}', 'CheckinController@delete');

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
