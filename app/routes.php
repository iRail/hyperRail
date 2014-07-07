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

// OAuth via external provider
Route::get('/oauth/{provider}', 'OAuthLoginController@getLogin');


Route::post('foo/bar', function() 
{
exit('successfull');
});

Route::get('foo/bar', function() 
{
return 'successhgjhgffull';
});

// OAuth server iRail
// issue: when sending a POST request to /token, it automatically takes the GET route -> can't get a token
Route::get('/tokenform', function()
	{
		return View::make('tokenform');
	});
Route::post('/token', 'TokenController@postToken');
Route::get('/resource', 'ResourceController@getResource');
Route::get('/authorize', 'AuthorizeController@authorize');
Route::post('/authorize', 'AuthorizeController@authorize');

// iRail login-functionality
Route::get('/register', 'IRailLoginController@getRegister');
Route::get('/login', 'IRailLoginController@getLogin');

Route::post('/login', 'IRailLoginController@postLogin');
Route::post('/register', 'IRailLoginController@postRegister');

Route::group(array('before' => 'auth'), function(){
	Route::get('/admin', 'AdminController@index');
	Route::get('/logout', 'IRailLoginController@logout');
});

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
