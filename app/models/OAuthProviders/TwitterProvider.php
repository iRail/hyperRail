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
	 * @return mixed
	 */
	public function getLogin() {
		// get data from input
        $code = Input::get('oauth_token');
        $verifier = Input::get('oauth_verifier');

        $twitterService = OAuth::consumer("Twitter","irail.dev/oauth/twitter");

        if (!empty($code)) {
            // This was a callback request from Twitter, get the token
            $token = $twitterService->requestAccessToken($code, $verifier);
            $token = $token->getRequestTokenSecret();

            // Send a request with it
            $result = json_decode($twitterService->request('account/verify_credentials.json'));

            $twitteruser = DB::table('users')->where('email', $result->screen_name)->first();

            if (empty($twitteruser)) {
                $data = new User;
                
                $data->provider = 'twitter';
                $data->token = $token;
                $data->email = $result->screen_name;
                $data->first_name = $result->name;
                $data->password = Hash::make($token);
                $data->activated = 1;
           
                $data->save();
            }
               
                $credentials = array(
                    'email' => $result->screen_name,
                    'password' => $token
                );

               return $credentials;

            
        }
        // if not ask for permission first
        else {
            // extra request needed for oauth1 to request a request token
            $token = $twitterService->requestRequestToken();

            // get twitterService authorization
            $url = $twitterService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));

            header('Location: ' . (string) $url);
            die();
        }
    }
}  

?>