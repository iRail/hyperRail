<?php

class AuthorizeController extends BaseController
{
    /**
     * The user is directed here by the client in order to authorize the client app
     * to access his/her data
     */
    public function authorize()
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
        if (empty($_POST)) {
          exit('
            <form method="post">
              <label>Do You Authorize TestClient?</label><br />
              <input type="submit" name="authorized" value="yes">
              <input type="submit" name="authorized" value="no">
            </form>');

          // $response_type = Input::get('response_type');
          // $client_id = Input::get('client_id');
          // $redirect_uri = Input::get('redirect_uri');
          // $state = Input::get('state');

          // exit( View::make('tokenform')->with('response_type', $response_type)->with('client_id',$client_id)->with('redirect_uri',$redirect_uri)->with('state',$state));
        }
        
        // print the authorization code if the user has authorized your client
        $is_authorized = ($_POST['authorized'] === 'yes');
        $server->handleAuthorizeRequest($request, $response, $is_authorized);
        if ($is_authorized) {
          // this is only here so that you get to see your code in the cURL request. Otherwise, we'd redirect back to the client
          $code = substr($response->getHttpHeader('Location'), strpos($response->getHttpHeader('Location'), 'code=')+5, 40);
          
          header('Location: ' . (string) $code);
          die();

          //exit("SUCCESS! Authorization Code: $code");

        }
        $response->send();
    }
}