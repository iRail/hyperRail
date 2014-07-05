<?php

class TokenController extends BaseController
{
    /**
     * This is called by the client app once the client has obtained
     * an authorization code from the Authorize Controller (@see OAuthServer\AuthorizeController).
     * If the request is valid, an access token will be returned
     */
    public function postToken()
    {
        // include our OAuth2 Server object
        require_once __DIR__.'/server.php';

        //dd(INPUT::get('grant_type'));

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        $server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
        
    }
}