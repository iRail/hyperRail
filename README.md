# iRail.be

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

### Step 1: Install dependencies ###

Run `composer update`. If you do not yet have composer, get it here: http://getcomposer.org

### Step 2: Update bootstrap/start.php for environments ###

Optional, but can be useful.

### Step 3: make app/storage writeable ###

```bash
chmod -R 777 storage
```

### Step 4: Set up hostname ###

In /app/config/app.php set the following to your personal hostname/preferences:

	'url' => 'http://irail.dev',    // with http
   	'url-short' => 'irail.dev',     // without http
   	
### Step 5: Set up resources ###

 * `npm install`
 * `bower install`
 * `grunt`

### Step 6: You're ready! ###

Usually you should be ready to get started by visiting the hostname you have set up. If it does not work, log an [issue](https://github.com/iRail/hyperRail/issues/new). We'll help you out and fix the documentation for everyone else.

In case you just want to update the stations list to the latest version, run: `composer update`

# License

We hereby put this work in the public domain under a [CC0 license](https://creativecommons.org/publicdomain/zero/1.0/)!

Feel free to attribute us at http://hello.irail.be.
