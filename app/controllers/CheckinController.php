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

		} else{
			if (Sentry::check()) {
				$user_id = Sentry::getUser()->id;
				$checkins = Checkin::where('user_id', $user_id)->get();
			} else{
				return Redirect::to("/login");
			}
		}

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

		if (isset($checkins[0])) {
			// set curl options
			$ch = curl_init();
			$request_headers[] = 'Accept: application/json';
	        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	        // ignore certificate
	        curl_setopt($ch , CURLOPT_SSL_VERIFYPEER , false );
			curl_setopt($ch , CURLOPT_SSL_VERIFYHOST , false );

			// make array to store results
			$results = array();

			// iterate over every checkin
			foreach ($checkins as $checkin) {

				// get the departure url from the checkin
				$specificStationUrl = $checkin['original']['departure'];
				// replace http with https
				$specificStationUrl = substr_replace($specificStationUrl, 's', 4, 0);
				// set url curl request
				curl_setopt($ch, CURLOPT_URL, $specificStationUrl);
				$r = curl_exec($ch);
				$r = json_decode($r, true);
				$r['@graph']['scheduledDepartureTime'] = date("H:i:s d-m-Y", strtotime($r['@graph']['scheduledDepartureTime']));
				array_push($results, $r['@graph']);
			}
			curl_close($ch);

			$checkins = $results;
		} else{
			$checkins = [];
		}
		
		// Sort checkins by departuretime
		function sortByDepartureTime($a, $b) {
    		return strtotime($a['scheduledDepartureTime']) - strtotime($b['scheduledDepartureTime']);
		}

		usort($checkins, 'sortByDepartureTime');
			



		switch ($val){
			case "application/json":
			case "application/ld+json":
				if (Sentry::check()) {
					return Response::make($checkins, 200)->header('Content-Type', 'application/ld+json')->header('Vary', 'accept');
				}
				return Response::make("Unauthorized Access", 403);
				break;
			case "text/html":
			default:
				return View::make('checkins.index', compact('checkins'));
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