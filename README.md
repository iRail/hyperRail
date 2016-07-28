# iRail.be

[![Software License](https://img.shields.io/badge/license-CC0-brightgreen.svg?style=flat)](https://creativecommons.org/publicdomain/zero/1.0/)
[![Dependency Status](https://david-dm.org/iRail/hyperRail.svg)](https://david-dm.org/iRail/hyperRail.svg)
[![devDependency Status](https://img.shields.io/david/dev/iRail/hyperRail.svg?style=flat)](https://david-dm.org/iRail/hyperRail#info=devDependencies)
[![Build Status](https://travis-ci.org/iRail/hyperRail.svg)](https://travis-ci.org/iRail/hyperRail)

[iRail.be](https://irail.be) is a web-application that uses data from http://api.irail.be ([source code](https://github.com/irail/irail).) to create a hyper-media driven application for the Belgian railway company.

_Looking for data? Check https://hello.irail.be for more info_

## Features

 * Implemented: [Content negotiation](https://en.wikipedia.org/wiki/Content_negotiation) for languages (en, fr, nl and de) and content-types (application/json and text/html)
 * Implemented: auto-complete for [all SNCB stations](https://irail.be/stations/NMBS)
 * Implemented: route planning interface
 * Planned: support for the [Hydra Linked Data vocabulary](http://www.hydra-cg.com/) for [hypermedia](https://en.wikipedia.org/wiki/Hypermedia)

_Want more features? Please do contribute by adding [feature requests](https://github.com/iRail/hyperRail/issues/new). Are you a developer? We accept pull requests!_

### Step 0: Requirements and clone ###

* PHP 5.5+ for Laravel project
* Apache

Clone the repository:

```bash
git clone https://github.com/iRail/hyperRail.git
```

### Step 1: Install / Update dependencies ###
In order to install the dependencies you have to run:
`composer install`
If you don't have composer, get it here: http://getcomposer.org

When installing this for the first time, also run this:

```
cp .env.example .env
php artisan key:generate
```
and edit your `.env` after your own taste (e.g., you may want to switch development mode off)

### Step 2: Update bootstrap/start.php for environments ###

Optional, but can be useful.

### Step 3: make app/storage writeable ###

```bash
chmod -R 777 storage
```

### Step 4: Set up resources ###

 * `npm install`
 * `bower install`
 * `grunt`

### Step 5: You're ready! ###

Usually you should be ready to get started by visiting the hostname you have set up. If it does not work, log an [issue](https://github.com/iRail/hyperRail/issues/new). We'll help you out and fix the documentation for everyone else.

In case you just want to update the stations list to the latest version, run: `composer update`

# License

We hereby put this work in the public domain under a [CC0 license](https://creativecommons.org/publicdomain/zero/1.0/)!

Feel free to attribute us at http://hello.irail.be.
