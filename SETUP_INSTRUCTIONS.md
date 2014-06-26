# Set up hyperRail

## Step 0: Requirements and clone

* PHP 5.3+ for Laravel project
* PHP 5.4+ for Way/Generators, if you have 5.4+ you can uncomment Way/Generators in /app/config/app.php
* Apache

Clone the repository:

```bash
git clone https://github.com/iRail/hyperRail.git
```

## Step 1: Setup a Vagrant box

Using [Vagrant](http://www.vagrantup.com/) you can easily setup a VM to work on. This VM consists of a basic web server with Apache. With it comes PHP and Composer. You can setup your vagrant box by running. This should take about 5 minutes.

```bash
vagrant up
```

## Step 2: Install dependencies

Run `composer update`. If you do not yet have composer, get it here: http://getcomposer.org

## Step 3: Update bootstrap/start.php for environments

Optional, but can be useful.

## Step 4: make app/storage writeable

```bash
chmod -R 777 app/storage
```

## Step 5: Set up hostname

In /app/config/app.php set the following to your personal hostname/preferences:

	'url' => 'http://irail.dev',    // with http
   	'url-short' => 'irail.dev',     // without http

You also need to add the hostname to your hosts file on your computer:

```bash
nano /etc/hosts
```

Add the following line:

`192.168.100.10 irail.dev`

## Step 5: You're ready!

Usually you should be ready to get started by visiting the hostname you have set up. If it does not work, log an [issue](https://github.com/iRail/hyperRail/issues/new). We'll help you out and fix the documentation for everyone else.