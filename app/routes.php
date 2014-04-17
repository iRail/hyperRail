<?php

use \hyperRail\Utils\AcceptHeader as AcceptHeader;

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
    // Accept header test
    // Content negotiation
    $acceptHeader = new AcceptHeader(Request::header('accept'));
    // TODO: negotiate content
    return json_encode($acceptHeader);
});

Route::get('/easyrdf', function(){
    $foaf = new EasyRdf_Graph("http://njh.me/foaf.rdf");
    $foaf->load();
    $me = $foaf->primaryTopic();
    echo "We're loading information from http://njh.me/foaf.rdf: " . $me->get('foaf:name');
});