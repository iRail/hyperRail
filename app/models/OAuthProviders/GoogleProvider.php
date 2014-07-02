<?php

use hyperRail\app\models\OAuthProviders\IOAuthProvider;

 /*
    |--------------------------------------------------------------------------
    | Google provider
    |--------------------------------------------------------------------------
 */

class GoogleProvider implements IOAuthProvider{

	/**
	 * Login Google-service
	 * 
	 * @return mixed
	 */
	public function login() {
		// get data from input
        $code = Input::get('code');

        // get google service
        $googleService = OAuth::consumer("Google");

        if (!empty($code)) {

            // This was a callback request from google, get the token
            $token = $googleService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($googleService->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

            $user = DB::select('select id from users where email = ?', array($result['email']));

            if (empty($user)) {
                $data = new User;
                $data->Username = $result['name'];
                $data->email = $result['email'];
                $data->first_name = $result['given_name'];
                $data->last_name = $result['family_name'];
                $data->save();
            }
            if (Auth::attempt(array('email' => $result['email']))) {

            return Redirect::to('/');
            }  else {
            echo 'error';    
            }
        }
        // if not ask for permission first
        else {
            // get googleService authorization
            $url = $googleService->getAuthorizationUri();

            // return to google login url
            // return Redirect::to((string) $url);
            header('Location: ' . $url);
        }
    }
	}

?>