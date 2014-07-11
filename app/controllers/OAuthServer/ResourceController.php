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

        // Verify that the access_token is valid
        if (!$server->verifyResourceRequest($request)) {
            $server->getResponse()->send();
            die;
        } 
        // response resource of the user corresponding with that token
        else {
            $access_token = Input::get('access_token');

            $userarray = DB::select('select * from users where access_token = ?',array($access_token));

            $user = $userarray[0];

            $api_response = array(
                'userdata' => array(
                    $user->id,
                    $user->first_name,
                    $user->last_name
                )
            );
            return json_encode($api_response);
        }
   }
}