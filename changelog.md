To-do features:
----------------------

* Twitterusers and regular users authorize if logged in efficiently.
* Extra Database: ID -> departure

Add column 'provider' to Users database Sentry
- Emailfield will be used for screenname or twitter-id of the user.
- Token will be saved inside password field so we can still use Sentry functionality with Laravel

With the twitter-id/screenname we can access it's friends by the following URL:

https://dev.twitter.com/docs/api/1/get/friends/ids

The token has to be passed with the GET-request. 

GOALS
------------------------
* API so you can "check-in", "see friends checked in"

Later work on the display:
* Left: check-in system
* Right: feed with friends who are checked in

* When checked in in a delayed train, the system tweets/posts to a friend you've made an appointment with, saying you have a delay. 


extra to-do:
--------------------
* bootstrap modal


Pieter: er is geen check meer of access_token vervallen is (normaal na 30sec al door gebruik van refreshtokens).
(In OAuth2\Controller\ResourceController.php : getAccessTokenData())
Later kijken voor refresh_tokens weer te gebruiken voor veiligheid.

Workflow authentication OAuth-resources:
----------------------------------------

- Server ontvangt een token -> checken of die cliënt geauthenticeerd is om gegevens van mensen met waar ze ingechecked zijn op te vragen.

- Om niet een compleet authenticatiesysteem te schrijven, willen we gebruik maken van Sentry:
De OAuth-cliënt moet daarom ook als User-record opgeslagen worden in de users-table van Sentry.
Dan kunnen we volgende functie gebruiken:
	$user = Sentry::authenticate($credentials, false);
Credentials: email->testclient paswoord->token

Vervolgens een extra kolom invoegen bij oauth_clients om bij te houden of de cliënt bevoegd is om alle checkin-gegevens op te vragen. Indien bevoegd, gegevens vrijgeven.



