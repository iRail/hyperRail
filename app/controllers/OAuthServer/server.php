<?php

        // Configuration comes from laravel's config: database.php
        $defaultdriver = Config::get('database.default');
        $dbname = Config::get("database.connections.{$defaultdriver}.database");
        $host = Config::get("database.connections.{$defaultdriver}.host");

        $dsn      = "{$defaultdriver}:dbname={$dbname};host={$host}";
        $username = Config::get("database.connections.{$defaultdriver}.username");
        $password = Config::get("database.connections.{$defaultdriver}.password");

        // error reporting (this is a demo, after all!)
        ini_set('display_errors',1);error_reporting(E_ALL);

        // Autoloading composer
        require_once('/vagrant/vendor/autoload.php');

        // $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
        $storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

        // Pass a storage object or array of storage objects to the OAuth2 server class
        $server = new OAuth2\Server($storage);

        // $server = new OAuth2\Server($storage, array(
        //     'allow_implicit' => true,
        // ));

        // Add the "Client Credentials" grant type (it is the simplest of the grant types)
        $server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

        // Add the "Authorization Code" grant type (this is where the oauth magic happens)
        $server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

        // create the server, and configure it to allow implicit
        $server = new OAuth2\Server($storage, array(
            'allow_implicit' => true,
        ));