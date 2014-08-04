#!/usr/bin/env bash

echo ">>> change directory"

cd /vagrant

echo ">>> Composer update"
composer update

# it's important that Sentry is first migrated, because we adapt the User table of Sentry
echo ">>> Migrating cartalyst/sentry tables"
php artisan migrate --package=cartalyst/sentry

echo ">>> Migrating OAuth2.0 usertables"
php artisan migrate


