	 	 	
#How to use iRail OAuth2.0 service in your application (made during #oSoc14)#

*In this how-to I'll explain briefly how to use the new iRail OAuth2.0-service for your website.*

##1. Register your application##
First, you need to register your application. Therefore we need a ```REDIRECT_URI```, a link where you want your users to be redirected to once they authorized, and your official ```APPLICATION_NAME```. Your users will be redirected to this URI when they confirm to give you access to their iRail data. 
In return we give you a ```CLIENTID``` so we can identify you.

There isn't a developer-page so to register you have to contact iRail with e-mail, Twitter... :)


##2. Make a link##
If you want to use data of a user, that user will first have to tell iRail he/she wants to share data with your application. 
Thereby, implement a link with the nice iRail-logo so the user gets redirected to:
```https://irail.be/authorize?response_type=token&client_id=YOURCLIENTID&redirect_uri=YOURREDIRECT_URI&state=xyz```

Simple HTML example:

```
<a href="https://irail.be/authorize?response_type=token&client_id=YOURCLIENTID&redirect_uri=YOURREDIRECT_URI&state=xyz">Link iRail</a>
```

When the user is logged in, he/she can click on yes or no to authorize and gets redirected to the ```REDIRECT_URI```. 


##3. Save the token##
If the user has authorized your application, he/she gets redirected back to your ```REDIRECT_URI``` with the access token as query parameter:

For example:

```
REDIRECT_URI?access_token=USERTOKEN&gws_rd=ssl
```

This token is important, because with this you can access data of the user without having to ask. Save this in your database.


##4. Access the resources##
With the users token in hand, you can access resources like “check-ins” of the user.

To access checkins:

```
https://irail.be/checkins?access_token=USERTOKEN
```

This will return an array of checkins JSON. 
A check-in holds the information of the departure that the user has scheduled.

Example output:

```json
[
{
"@id" : "http:\/\/irail.be\/stations\/NMBS\/008892007\/departures\/2014071610167a28fedbe2e337a68a83c4c050d6c795",
"delay" : "0",
"platform" : "7",
"routeLabel" : "IR 3608",
"scheduledDepartureTime" : "10:16:00 16-07-2014",
"stop" : "http:\/\/irail.be\/stations\/NMBS\/008892007",
"headsign" : "Antwerpen-Centraal",
"seeAlso" : "http:\/\/archive.irail.be\/irail?object=http%3A%2F%2Firail.be%2Fstations%2FNMBS%2F008892007%2Fdepartures%2F2014071610167a28fedbe2e337a68a83c4c050d6c795"
}, 
...
]
```



