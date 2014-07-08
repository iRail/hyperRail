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
            // Send a request with it
            $result = json_decode($twitterService->request('account/verify_credentials.json'));


            // for testing, left the email-column for saving the screen_name
            // doesnt work because twitteruser model has to be made 
            $twitteruser = DB::table('twitterUsers')->where('email', $result->screen_name)->first();

            if (empty($twitteruser)) {
                $data = new TwitterUser;
                
                $data->token = INPUT::get('oauth_token');
                $data->departure = "blaaa";
                $data->name = $result->name;
                $data->email = $result->screen_name;
                //$data->first_name = $result['given_name'];
                //$data->last_name = $result['family_name'];
                $data->save();
            }

            /**
                TODO:
                Check what er in array('email' => $result->screen_name)) staat en waarom dit false geeft met AUTH
                zit fout misschien in IRailLoginController omdat deze aangeroepen wordt en enkel op reguliere users kan checken?
            */

                print_r(array('email' => $result->screen_name));

            if (Auth::attempt(array('email' => $result->screen_name))) {
                return Redirect::to('/');
            }  else {
                echo 'error';    
            }
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