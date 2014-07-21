<?php

class CheckinController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// OAuth API
		if(Input::has('access_token')){
				// include our OAuth2 Server object
			require_once __DIR__.'/OAuthServer/server.php';

			$request = OAuth2\Request::createFromGlobals();
			$response = new OAuth2\Response();

	        // Verify that the access_token is valid
			if (!$server->verifyResourceRequest($request)) {
				$server->getResponse()->send();
				die;
			} 

			$access_token = Input::get('access_token');

			$userarray = DB::select('select * from users where access_token = ?', array($access_token));

			if (!count($userarray)) {
				return json_encode("No correct parameter given. irail.dev/checkins?access_token=...");
			}

			$user = $userarray[0];

	        // checkins in JSON-format
			$checkins = Checkin::where('user_id', $user->id)->get();

			$val = "application/json";
		}
		else{
				//TODO: remove code duplication and put this in BaseController
			$negotiator = new \Negotiation\FormatNegotiator();
			$acceptHeader = Request::header('accept');
			$priorities = array('text/html', 'application/json', '*/*');
			$result = $negotiator->getBest($acceptHeader, $priorities);
			$val = "text/html";
	        //unless the negotiator has found something better for us
			if (isset($result)) {
				$val = $result->getValue();
			}

			if (Sentry::check()) {
				$user = Sentry::getUser();
				$checkins = Checkin::where('user_id', $user->id)->get();
			} else{
				return Redirect::to('/login');
			}
		}

		// parse the departures in an array
		$data = "[";
		foreach($checkins as $checkin) {
			$data .= "{ \"departure\": '". "$checkin->departure" . "'},";
		}
		$data = substr_replace($data, '', -1); // to get rid of extra comma
		$data .= "]";

		switch ($val){
			case "application/json":
			case "application/ld+json":
			if (Sentry::check()) {
				return Response::make($data, 200)->header('Content-Type', 'application/ld+json')->header('Vary', 'accept');
			}
			return Response::make("Unauthorized Access", 403);
			break;
			case "text/html":
			default:
			return View::make('checkins.index', array('checkins' => $checkins));
			break;
		}
	}



	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public static function store()
	{
		$departure = Input::get('departure');

		if (!Sentry::check()) {
			return Redirect::to('login');
		} else {
			$data = new Checkin;
			$user = Sentry::getUser();

			$data->user_id = $user->id;
			$data->departure = $departure;

	        // $data->save;
			if (!CheckinController::isAlreadyCheckedIn($departure, $user)) {
				$data->save();
			}
		}

		$departure = str_replace("http://", "https://", $departure);

		return Redirect::to($departure)->header('Access-Control-Allow-Origin', 'https://irail.dev');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public static function destroy($departure) {

// OAuth API
		if(Input::has('access_token')){
				// include our OAuth2 Server object
			require_once __DIR__.'/OAuthServer/server.php';

			$request = OAuth2\Request::createFromGlobals();
			$response = new OAuth2\Response();

	        // Verify that the access_token is valid
			if (!$server->verifyResourceRequest($request)) {
				$server->getResponse()->send();
				die;
			} 

			$access_token = Input::get('access_token');

			$userarray = DB::select('select * from users where access_token = ?', array($access_token));

			if (!count($userarray)) {
				return json_encode("No correct parameter given. irail.dev/checkins/{departure_URI}?access_token=...");
			}

			$user = $userarray[0];

	        // delete the corresponding URI
			Checkin::where('user_id', $user->id)->where('departure', $departure)->delete();
		}
		else{
			if (!Sentry::check()) {
				return Redirect::to('login');
			} else {
				$user = Sentry::getUser();
				Checkin::where('user_id', $user->id)->where('departure', $departure)->delete();
			}
		}

		return Redirect::to('/checkins/', '303')->header('Access-Control-Allow-Origin', 'https://irail.dev');
	}

	public static function isAlreadyCheckedIn($departure, $user) {
		return (count(Checkin::where('user_id', $user->id)->where('departure', $departure)->first()) > 0);
	}

}