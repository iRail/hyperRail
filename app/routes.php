<?php

use \hyperRail\Utils\ContentNegotiation\AcceptHeaderParsedArray as AcceptHeaderParsedArray;
use \hyperRail\Utils\ContentNegotiation\AcceptHeaderQEvaluator as qEvaluator;

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

Route::get('/',  function(){
    $acceptHeader = new AcceptHeaderParsedArray(Request::header('accept'));
    // The q parameter is important because it tells us which format
    // the end user would like to receive; so we need to evaluate it first!
    return json_encode(qEvaluator::evaluate($acceptHeader));
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

Route::get('/tests/easyrdf', function(){
    $foaf = new EasyRdf_Graph("http://njh.me/foaf.rdf");
    $foaf->load();
    $me = $foaf->primaryTopic();
    echo "We're loading information from http://njh.me/foaf.rdf: " . $me->get('foaf:name');
});