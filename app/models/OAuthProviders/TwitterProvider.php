<?php

use hyperRail\app\models\OAuthProviders\IOAuthProvider;

 /*
    |--------------------------------------------------------------------------
    | Twitter provider
    |--------------------------------------------------------------------------
 */

class TwitterProvider implements IOAuthProvider{

    /**
     * Login Twitter-service
     * 
     * Two scenarios when the user needs to be logged in:
     * - For normal login
     * - For authorizing third-party app (iRail OAuth2.0-service)
     *
     * @return mixed
     */
    public function getLogin($redirect_uri) {
        // get data from input
        $code = Input::get('oauth_token');
        $verifier = Input::get('oauth_verifier');

        // when the user wants to log in with an iframe to authorize
        // and Twitter hasn't been authorized
        if (!empty(Input::get('client_id')) && empty($code)) {
            // display an authorization form
            $response_type = Input::get('response_type');
            $client_id = Input::get('client_id');
            $redirect_uri = Input::get('redirect_uri');
            $state = Input::get('state');

            $querystring = '?';

            $queryparams = array('response_type' => $response_type,
             'client_id' => $client_id,
             'redirect_uri' => $redirect_uri,
             'state' => $state);

            foreach($queryparams as $key => $value){
                $querystring .= $key . '=' . $value;
                $querystring .= '&';
            }
            $redirect_uri = URL::to('oauth/twitter' . $querystring);
        }
        else if ( empty(Input::get('client_id')) ) {
            $redirect_uri = URL::to('oauth/twitter');
        }

        // loads our Twitter credentials
        // Second parameter is the redirect URL
        $twitterService = OAuth::consumer("Twitter",$redirect_uri);

        // Second step, callback when the user has authorized Twitter
        if ( !empty($code) ) {
            // This was a callback request from Twitter, get the token
            $token = $twitterService->requestAccessToken($code, $verifier);            
            $access_token = $token->getAccessToken();
            $request_token = $token->getRequestTokenSecret();

            // Send a request with it
            $result = json_decode($twitterService->request('account/verify_credentials.json'));

            $twitteruser = DB::table('users')->where('email', $result->screen_name)->first();

            if ( empty($twitteruser) ) {
                $data = new User;
                
                $data->provider = 'twitter';
                $data->token = $access_token;
                $data->email = $result->screen_name;
                $data->first_name = $result->name;
                $data->password = Hash::make($request_token);
                $data->activated = 1;
           
                $data->save();
            }
               
            $credentials = array(
                'email' => $result->screen_name,
                'password' => $request_token
            );

           return $credentials;            
        }
        // First step, if not ask for permission first
        else {
            // extra request needed for oauth1 to request a request token
            $token = $twitterService->requestRequestToken();

            // get twitterService authorization
            $url = $twitterService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));

            // when the user wants to authorize iRail in an iframe, but isn't logged in
            // query parameters need to be passed: client_id, response_type etc.
            if(!empty(Input::get('client_id'))){
                // display an authorization form
                $response_type = Input::get('response_type');
                $client_id = Input::get('client_id');
                $redirect_uri = Input::get('redirect_uri');
                $state = Input::get('state');

                $querystring = '&';

                $queryparams = array('response_type' => $response_type,
                                     'client_id' => $client_id,
                                     'redirect_uri' => $redirect_uri,
                                     'state' => $state);

                foreach ($queryparams as $key => $value) {
                    $querystring .= $key . '=' . $value;
                    $querystring .= '&';
                }

                header('Location: ' . (string) $url . $querystring);
                die();
            }
            // normal login
            else{
                header('Location: ' . (string) $url);
                die();
            }
        }
    }

    /**
     * Get Twitter-friends of the current logged in user
     *  todo
     * @return mixed
     */
    public function getFriends($user_id) {    
            // check if user is logged in with Twitter
            $results = DB::select('select * from users where id = ? and provider = "twitter"', array($user_id));

            dd($results);

            $twitterService = OAuth::consumer("Twitter","irail.dev/oauth/twitter");

            $result2 = json_decode($twitterService->request('friends/ids.json?cursor=-1&screen_name=brechtvdv&count=5000'));
            //$result2 = json_decode($twitterService->request('friends/list.json?cursor=-1&screen_name=brechtvdv&skip_status=true&include_user_entities=false'));
              
            dd($result2);   
        
    } 

} 

?>