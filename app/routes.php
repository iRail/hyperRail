<?php

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
    // Set up negotiation
    $negotiator   = new \Negotiation\FormatNegotiator();
    // Get accept header
    $acceptHeader = Request::header('accept');
    // Test for priorities
    $priorities   = array('text/html', 'application/json', '*/*');
    // Get the best formats
    $result = $negotiator->getBest($acceptHeader);
    // Dump value
    var_dump($result->getValue());
    // Dump quality
    var_dump($result->getQuality());
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