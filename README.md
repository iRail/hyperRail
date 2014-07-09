## hyperRail

Introducing the new iRail.be. The new iRail will be hypermedia driven!

We are using the following technologies:

- laravel 4.1
- bootstrap-sass
- angularjs + angularui
- and more (see composer)

## Core concepts

Here below you'll find a brief description of the core concepts that play an important role in the new iRail.

### Content negotiation

#### Language

When a page is requested, the language preference is delegated from either the URL (using the GET parameter 'lang') or via accept headers. Accept headers take preference over any parameters.

#### Format (API)

iRail doesn't use a classic API on another URL. There's no 'irail.be/api', instead you specify in the **accept headers** what kind of resource you'd like to request. By default, when visiting with a browser, you'll get a webpage that can be used by end-users.

You can also ask the application (iRail.be) for other kinds of resources. For example, you can ask for a JSON version of the data that the routeplanner serves.

In order to ask the API to serve you JSON (and not just the plain HTML that you get when you visit irail.be in your browser), send a request to `irail.be/route` but specify the requested content-type in your accept headers, e.g. `application/ld+json`.

### Hypermedia interface

Data returned through the API (when specifically requesting `application/ld+json`) should be structured and contains information about other possible paths (interaction) and contexts. Following the HATEOAS principle: 

> A REST client needs no prior knowledge about how to interact with any particular application or server beyond a generic understanding of hypermedia.

### Data dumps

Data dumps and the data that is used (and will be used and queryable in the future) are available at <a href="http://archive.irail.be">http://archive.irail.be</a>.

### Authentication interface

OAuth URI namespace (WIP): 

In order to log in with a oAuth provider, send a request to 'irail.be/oauth/{provider}' (supported provider: 'google'). 

The LoginController checks what provider it is and executes the corresponding login()-method of the specific provider. Every provider has it's own Provider-class which implements an OAuthProvider-interface.

Inside the login()-method of a Provider-class happens the oAuth-process.


### Modify database.php 
Vagrant automatically uses 'root' as username and password. For this reason you should go to app/config and create a folder named 'productie' and copy database.php in it.
Also put this folder in .gitignore.
Next, you open that file in 'productie' and modify the mysql section to this:

```bash
		'mysql' => array(
			'driver'    => 'mysql',
			'host'      => 'localhost',
			'database'  => 'hyperrail',
			'username'  => 'root',
			'password'  => 'root',
			'charset'   => 'utf8',
			'collation' => 'utf8_unicode_ci',
			'prefix'    => '',
		),
```

Later when entering production, modify the vagrant file and app/config/database.php.

### Connect to vagrant database

* Open MySQL Workbench or any other tool you like.
* Connect to a new database using the following settings:

```bash
Connection Method: Standard TCP/IP over SSH
SSH Hostname: 127.0.0.1:2222
SSH Username: root
SSH Keyfile: select ~/.vagrant.d/insecure_private_key
MySQL hostname: 127.0.0.1
MySQL Server Port: 3306
Username: root
Password: root
port: 3306
```
### OAuth2.0-server API (WIP)

First of all your application has to be registered in our database with a client-id, clientsecret and redirect-url of your application. Contact us if you want to use our API.

To make an oauth_client, execute following example sql-command:
INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ("testclient", "testpass", "http://fake/");


A token can easily be asked by going to:
https://irail.be/authorize?response_type=token&client_id="yourid"&redirect_uri="yoursite"&state=xyz

The token can be parsed from the query-parameter "access_token".

### Thanks to

Special attribution to Melih Bilgil for the icon set. (http://picol.org/icon_library.php)
