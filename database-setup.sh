#!/usr/bin/env bash

echo ">>> change directory"

cd /vagrant

echo ">>> Composer update"
composer update

echo ">>> Migrating OAuth2.0 usertables"

php artisan migrate

echo ">>> Migrating cartalyst/sentry tables"
php artisan migrate --package=cartalyst/sentry

