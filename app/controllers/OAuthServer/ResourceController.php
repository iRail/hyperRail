<?php

class ResourceController extends BaseController
{
    /**
     * This is called by the client app once the client has obtained an access
     * token for the current user.  If the token is valid, the resource (in this
     * case, the "friends" of the current user) will be returned to the client
     */
    // public function resource(Application $app)
    public function getResource()
    {
        // include our OAuth2 Server object
        require_once __DIR__.'/server.php';

        // replace the expired token check
        $this->config['access_lifetime'] !== false && time() > $token["expires"]

        // Handle a request for an OAuth2.0 Access Token and send the response to the client
        if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
            $server->getResponse()->send();
            die;
        }
        echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));

        // // get the oauth server (configured in src/OAuth2Demo/Server/Server.php)
        // $server = $app['oauth_server'];

        // // get the oauth response (configured in src/OAuth2Demo/Server/Server.php)
        // $response = $app['oauth_response'];

        // if (!$server->verifyResourceRequest($app['request'], $response)) {
        //     return $server->getResponse();
        // } else {
        //     // return a fake API response - not that exciting
        //     // @TODO return something more valuable, like the name of the logged in user
        //     $api_response = array(
        //         'friends' => array(
        //             'john',
        //             'matt',
        //             'jane'
        //         )
        //     );
        //     return new Response(json_encode($api_response));
        // }
    }
}