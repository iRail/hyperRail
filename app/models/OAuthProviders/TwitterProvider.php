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
	public function login() {
		// get data from input
        $code = Input::get('code');

        // get google service
        $twitterService = OAuth::consumer("Twitter", "http://irail.dev/");

        if (!empty($code)) {

            // This was a callback request from google, get the token
            $token = $twitterService->requestAccessToken($code);

            // Send a request with it
            $result = json_decode($twitterService->request('account/verify_credentials.json'));

            echo 'result: <pre>' . print_r($result, true) . '</pre>';

            // $user = DB::select('select id from users where email = ?', array($result['email']));

            // if (empty($user)) {
            //     $data = new User;
            //     $data->Username = $result['name'];
            //     $data->email = $result['email'];
            //     $data->first_name = $result['given_name'];
            //     $data->last_name = $result['family_name'];
            //     $data->save();
            // }
            // if (Auth::attempt(array('email' => $result['email']))) {

            // return Redirect::to('/');
            // }  else {
            // echo 'error';    
            // }

            dd($result);
        }
        // if not ask for permission first
        else {
            // extra request needed for oauth1 to request a request token
            $token = $twitterService->requestRequestToken();

            // get twitterService authorization
            $url = $twitterService->getAuthorizationUri(array('oauth_token' => $token->getRequestToken()));

            //dd($url);

            //return Redirect::to((string) $url);
            header('Location: ' . $url);
        }
    }
	}

?>