# Set up hyperRail

## Step 0: Requirements and clone

* PHP 5.3.7+ for Laravel project
* PHP 5.4+ for Way/Generators, if you have 5.4+ you can uncomment Way/Generators in /app/config/app.php
* MCrypt PHP extension
* An Apache or nginx web server

Clone the repository:

```bash
git clone https://github.com/iRail/hyperRail.git
```

## Step 1: Install dependencies

Run `composer update`. If you do not yet have composer, get it here: http://getcomposer.org
If you get any errors concerning MCRYPT, please refer to the following links:

* Mac OSX: http://stackoverflow.com/questions/16830405/laravel-requires-the-mcrypt-php-extension
* Ubuntu: ```bash
			sudo apt-get install php5-mcrypt
			```


## Step 2: Update bootstrap/start.php for environments

Optional, but can be useful.

## Step 3: make app/storage writeable

```bash
chmod a+w -R app/storage
```

## Step 4: Set up hostname

In /app/config/app.php set the following to your personal hostname/preferences:

	'url' => 'http://irail.dev',    // with http
   	'url-short' => 'irail.dev',     // without http

## Step 5: Set up vagrant

If 'vagrant' is not yet installed on your machine, get it here: https://www.vagrantup.com/downloads

```bash
vagrant up
```
This may take some time, but afterwards you can connect to the virtual database.

## Step 6: Grunt
If 'grunt' is not installed on your machine, please visit: http://gruntjs.com/getting-started
Grunt also requires 'compass' so if this is also not installed, please visit: http://compass-style.org/install/

Now run the following command in the root directory of Hyperrail:

```bash
npm install
bower install
composer install

grunt 

Later on:
composer update
```

## Step 7: Access tokens lifetime

Bshaffer's library works with refresh tokens.
Go to
```
/vendor/bshaffer/oauth2-server-php/src/OAuth2/Controller/ResourceController.php:
```

and comment te next section:

```
public function getAccessTokenData(RequestInterface $request, ResponseInterface $response)
{		
	...
	//elseif( time() > $token["expires"] ){             
    //    $response->setError(401, 'invalid_token', 'The access token provided has expired');
    //}
    ...
}	
```
This makes sure our tokens won't expired.

## Step 8: Add credentials

Add your Twitter credentials to app/config/packages/artdarek/oauth-4-laravel/config.php

## Step 9: You're ready!

Usually you should be ready to get started by visiting the hostname you have set up. If it does not work, log an [issue](https://github.com/iRail/hyperRail/issues/new). We'll help you out and fix the documentation for everyone else.
