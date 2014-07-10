<?php

class AuthorizeController extends BaseController
{
    /**
     * The user is directed here by the client in order to authorize the client app
     * to access his/her data
     */
    public function getAuthorize()
    {

        // include our OAuth2 Server object
        require_once __DIR__.'/server.php';

        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        // validate the authorize request
        if (!$server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        // display an authorization form
        $response_type = Input::get('response_type');
        $client_id = Input::get('client_id');
        $redirect_uri = Input::get('redirect_uri');
        $state = Input::get('state');

        $url = URL::to('authorize');

        $queryparams = array('response_type'    => $response_type,
                                 'client_id'    => $client_id,
                                 'redirect_uri' => $redirect_uri,
                                  'state'       => $state);

        $url .= '?';
        foreach($queryparams as $key => $value){
            $url .= $key . '=' . $value;
            $url .= '&';
        }

        return View::make('tokenform')->with('url', $url);
    }

    public function postAuthorize(){
        // include our OAuth2 Server object
        require_once __DIR__.'/server.php';

        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

       // validate the authorize request
        if (!$server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        // If the user has clicked 'yes', redirect with access_token as queryparameter
        $is_authorized = ($_POST['authorized'] === 'Yes');
        $server->handleAuthorizeRequest($request, $response, $is_authorized);
        if ($is_authorized) {
            // url = redirect_uri#access_token=...&otherparameters
            $url = $response->getHttpHeader('Location');
            // parse the access_token
            $access_token_length = strpos($url,'&') - strpos($url, '=')-1;
            $access_token = substr($url, strpos($url, '=')+1, $access_token_length);

            $redirect_uri = substr($url, 0, strpos($url,'#'));
            $returnpage = (string) $redirect_uri . '?access_token=' . (string) $access_token;

            header('Location: ' . $returnpage);
            die();
        }                   
    }
}