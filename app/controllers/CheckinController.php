<?php

class CheckinController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
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
	                    return json_encode("No correct parameter given. irail.dev/resource/checkins");
	        }

	        $user = $userarray[0];

	        $user_id = $user->id;

	        $url = 'https://irail.dev/checkins/' . $user_id;
                    //$data = array('key1' => 'value1', 'key2' => 'value2');

                    // use key 'http' even if you send the request to https://...
                    $options = array(
                        'http' => array(
                            'header'  => "accept: application/json",
                            'method'  => 'GET',
                            //'content' => http_build_query($data),
                            ),
                        );
                    $context  = stream_context_create($options);
                    $result = file_get_contents($url, false, $context);
                    
                    // response resource of the user corresponding with that token
            return $result;
	       
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

	        switch ($val){
	            case "application/json":
	            case "application/ld+json":
	            	if (Sentry::check()) {
	            		$data = json_encode($data);
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
		
	}

	/**
	 * Display a listing of resources of an id
	 *
	 * @return  response
	 */
	public function show($id)
	{
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
		$checkins = Checkin::where('user_id', $id)->get()->toJson();

		switch ($val){
            case "application/json":
            case "application/ld+json":
            	return Response::make($checkins, 200)->header('Content-Type', 'application/ld+json')->header('Vary', 'accept');
            break;
            case "text/html":
            default:
            	return View::make('checkins.show')->with('checkins', $checkins);
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

	    return Redirect::to($departure);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public static function destroy() {
		$departure = Input::get('departure');

		if (!Sentry::check()) {
			return Redirect::to('login');
		} else {
	        $user = Sentry::getUser();
	    	Checkin::where('user_id', $user->id)->where('departure', $departure)->delete();
	    }

	    return Redirect::to($departure);
	}

	public static function isAlreadyCheckedIn($departure, $user) {
		return (count(Checkin::where('user_id', $user->id)->where('departure', $departure)->first()) > 0);
	}

}