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
		return View::make('home.login');
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
		}

		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
			echo 'User Already Exists';
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
				return Redirect::to('admin');
			}

		}
		catch (\Exception $e)
		{
			return Redirect::to('/login')->withErrors(array('login' => 'Error'));
		}
	}


	public function logout()
	{
		Sentry::logout();
		return Redirect::to('/route');
	}


}