# Set up hyperRail

## Step 0: Requirements and clone

* PHP 5.3+ for Laravel project
* PHP 5.4+ for Way/Generators, if you have 5.4+ you can uncomment Way/Generators in /app/config/app.php
* Apache

Clone the repository:

```bash
git clone https://github.com/iRail/hyperRail.git
```

## Step 1: Install dependencies

Run `composer update`. If you do not yet have composer, get it here: http://getcomposer.org

## Step 2: Update bootstrap/start.php for environments

Optional, but can be useful.

## Step 3: make app/storage writeable

```bash
chmod -R 777 app/storage
```

## Step 4: Set up hostname

In /app/config/app.php set the following to your personal hostname/preferences:

	'url' => 'http://irail.dev',    // with http
   	'url-short' => 'irail.dev',     // without http

### Step 5: Set up sentry for the user database

php artisan migrate --package=cartalyst/sentry

### Step 6: Setup database config for OAuth2.0-server database

Change in the file app/Server.php to your own database-configuration: 
$storage = new OAuth2\Storage\Pdo(array('dsn' => 'mysql:dbname=hyperrail;host=localhost', 'username' => 'root', 'password' => 'root'));

## Step 5: You're ready!

Usually you should be ready to get started by visiting the hostname you have set up. If it does not work, log an [issue](https://github.com/iRail/hyperRail/issues/new). We'll help you out and fix the documentation for everyone else.
