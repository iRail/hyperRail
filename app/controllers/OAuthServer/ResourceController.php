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

        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        // Verify that the access_token is not expired
        if (!$server->verifyResourceRequest($request)) {
            $server->getResponse()->send();
            die;
        } else {
            //echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));
            // return a fake API response - not that exciting
            // @TODO return something more valuable, like the name of the logged in user
            $api_response = array(
                'friends' => array(
                    'Sam',
                    'Brecht',
                    'Michiel'
                )
            );
            return json_encode($api_response);
        }
   }
}