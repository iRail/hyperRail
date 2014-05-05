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

Route::get('/jsonld', function(){

    $graph = new EasyRdf_Graph();
    if (empty($_REQUEST['data'])) {
        $graph->load('http://hyperrail.dev/NMBS.ttl', 'turtle');
    }

    $format = EasyRdf_Format::getFormat('jsonld');
    $output = $graph->serialise($format);
    if (!is_scalar($output)) {
        $output = var_export($output, true);
    }
    // Expand JsonLD
    $expanded = JsonLD::expand($output);
    print JsonLD::toString($expanded, true);


});

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