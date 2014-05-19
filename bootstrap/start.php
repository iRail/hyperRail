<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
*/

$env = $app->detectEnvironment( function() {

    $haystack = __DIR__; // Catch the directory path

    // Set the boolean
    $isLocal = strpos($haystack, 'Users/nicoverbruggen/Repositories/hyperRail');
    $isLocalVagrant = strpos($haystack, 'var/www/html/');
    $isDevelopment = strpos($haystack, 'var/www/irail.be/development/');
    $isTest = strpos($haystack, 'var/www/irail.be/test/');
    $isProduction = strpos($haystack, 'var/www/irail.be/web/');

    // Return the correct environment
    if ($isLocal) return "local";
    if ($isLocalVagrant) return "vagrant";
    if ($isDevelopment) return "development";
    if ($isTest) return "test";
    if ($isProduction) return "production";

});

/*
|--------------------------------------------------------------------------
| Bind Paths
|--------------------------------------------------------------------------
|
| Here we are binding the paths configured in paths.php to the app. You
| should not be changing these here. If you need to change these you
| may do so within the paths.php file and they will be bound here.
|
*/

$app->bindInstallPaths(require __DIR__.'/paths.php');

/*
|--------------------------------------------------------------------------
| Load The Application
|--------------------------------------------------------------------------
|
| Here we will load this Illuminate application. We will keep this in a
| separate location so we can isolate the creation of an application
| from the actual running of the application with a given request.
|
*/

$framework = $app['path.base'].'/vendor/laravel/framework/src';

require $framework.'/Illuminate/Foundation/start.php';

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
