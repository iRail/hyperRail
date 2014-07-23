<?php

class IRailLoginController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default IRailLogin Controller
	|--------------------------------------------------------------------------
	|
	|	Controller to register or login a user to the iRail-users database.
	|	
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function getRegister()
	{
		return View::make('home.register');
	}

	public function getLogin()
	{
		// when the user isn't logged in and wants to authorize
		if(!empty(Input::get('client_id'))) {

		    // display an authorization form
	        $response_type = Input::get('response_type');
	        $client_id = Input::get('client_id');
	        $redirect_uri = Input::get('redirect_uri');
	        $state = Input::get('state');

	   		$url = URL::to('login');
	   		$twitter_url = URL::to('oauth/twitter');

	        $queryparams = array('response_type' => $response_type,
	           'client_id' => $client_id,
	           'redirect_uri' => $redirect_uri,
	           'state' => $state);

	        $url .= '?';
	        $twitter_url .= '?';
	        foreach($queryparams as $key => $value){
	            $url .= $key . '=' . $value;
	            $url .= '&';

	            $twitter_url .= $key . '=' . $value;
	            $twitter_url .= '&';
	        }

	        return View::make('home.login-iframe')->with(array('url' => $url, 'twitter_url' => $twitter_url));
		}
		else{
			return View::make('home.login');
		}
	}


	public function postRegister()
	{	
		try
		{
			$user = Sentry::createUser(array(
				'first_name' => Input::get('first_name'),
				'last_name' => Input::get('last_name'),
				'email' => Input::get('email'),
				'password' => Input::get('password'),
				'activated' => true,

				));
			return Redirect::to('/login');
		}

		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
			return Redirect::to('/register')->withErrors(array('login' => Lang::get('client.userAlreadyExists')));
		}
	}


	public function postLogin()
	{
		$credentials = array(
			'email' => Input::get('email'),
			'password' => Input::get('password'),
			);

		try{

			$user = Sentry::authenticate($credentials, false);

			if($user)
			{
    			$user->save();

    			// third-party authorization
    			if (!empty(Input::get('client_id'))) {
		            $response_type = Input::get('response_type');
		            $client_id = Input::get('client_id');
		            $redirect_uri = Input::get('redirect_uri');
		            $state = Input::get('state');

		            $url = URL::to('authorize');

		            $queryparams = array('response_type' => $response_type,
		               'client_id' => $client_id,
		               'redirect_uri' => $redirect_uri,
		               'state' => $state);

		            $url .= '?';
		            foreach ($queryparams as $key => $value) {
		                $url .= $key . '=' . $value;
		                $url .= '&';
		            }

    				return Redirect::to($url);
    			}
				return Redirect::to('/route');
			}

		}
		catch (\Exception $e)
		{
			return Redirect::to('/login')->withErrors(array('login' => Lang::get('client.wrongUsernameOrPassword')));
		}
	}


	public function logout()
	{
		Sentry::logout();
		return Redirect::to('/route');
	}


}