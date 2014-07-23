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

        $response_type = Input::get('response_type');
        $client_id = Input::get('client_id');
        $redirect_uri = Input::get('redirect_uri');
        $state = Input::get('state');

        // If user is not logged in, redirect to login
        if (!Sentry::check()) {
            // display an authorization form
            $url = URL::to('login');

            $queryparams = array('response_type' => $response_type,
               'client_id' => $client_id,
               'redirect_uri' => $redirect_uri,
               'state' => $state);

            $url .= '?';
            foreach($queryparams as $key => $value){
                $url .= $key . '=' . $value;
                $url .= '&';
            }

            return Redirect::to($url);
        }
        // If logged in, ask to authorize
        else {

            $url = URL::to('authorize');

            $queryparams = array('response_type' => $response_type,
               'client_id' => $client_id,
               'redirect_uri' => $redirect_uri,
               'state' => $state);

            $url .= '?';
            foreach($queryparams as $key => $value){
                $url .= $key . '=' . $value;
                $url .= '&';
            }

            // application name from database corresponding with the client_id
            $results = DB::select('select * from oauth_clients where client_id = ?', array($client_id))[0];

            $appName = $results->application_name;

            return View::make('tokenform')->with('url', $url)->with('appName',$appName);
        }
    }

    public function postAuthorize(){
        // include our OAuth2 Server object
        require_once __DIR__ . '/server.php';

        $request = OAuth2\Request::createFromGlobals();
        $response = new OAuth2\Response();

        // validate the authorize request
        if (!$server->validateAuthorizeRequest($request, $response)) {
            $response->send();
            die;
        }

        if(!Sentry::check()){
          die('not logged in');
        }

        // get current logged in user
        $user = Sentry::getUser();

        // If the user has clicked 'yes', redirect with access_token as queryparameter
        $is_authorized = ($_POST['authorized'] === 'Yes');

        $server->handleAuthorizeRequest($request, $response, $is_authorized, $user['id']);

        if (!$is_authorized) {
          $url = $response->getHttpHeader('Location');
          header('Location: ' . $url);
          die();
        }

        // Fix url, use GET params instead of hash
        $response->send();
        // header('Location: ' . str_replace('#' , '?', $response->getHttpHeader('Location')));
        // die();
   }
}