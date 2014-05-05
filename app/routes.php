<?php

use ML\JsonLD\JsonLD;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@showWelcome');

Route::get('/apitest', 'ApiController@test');

Route::get('/stations', function(){

});

Route::get('/stations/{id}', function($id){
    return $id;
});

Route::get('/stations/{id}/arrivals', function($id){
    return $id;
});

Route::get('/stations/{id}/departures', function($id){
    return $id;
});

Route::get('/vehicle', function(){

});

Route::get('/parkings', function(){

});

Route::get('/route', function(){

});

Route::get('/parkings/{location_id}', function($location_id){
    return $location_id;
});