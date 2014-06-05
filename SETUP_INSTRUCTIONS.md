# Set up hyperRail

## Step 0: Requirements

* PHP 5.3+ for Laravel project
* PHP 5.4+ for Way/Generators, if you have 5.4+ you can uncomment Way/Generators in /app/config/app.php
* Apache

## Step 1: Install dependencies

This is Laravel. You know the drill, run `composer update`.

## Step 2: Update bootstrap/start.php for environments

Optional, but can be useful.

## Step 3: Set up hostname

In /app/config/app.php set the following to your personal hostname/preferences:

	'url' => 'http://irail.dev',    // with http
   	'url-short' => 'irail.dev',     // without http

## Step 4: You're ready!

Usually you should be ready to get started by visiting the hostname you set up.