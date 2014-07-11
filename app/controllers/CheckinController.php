<?php

class CheckinController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function postIndex()
	{
		if(Request::isJson()){
			$checkin = new Checkin;
			$data = Request::getContent();
			return json_decode($data);

		}
		return Response::make("Bad Request", 400);

	}

	public function getIndex()
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

       	if (Sentry::check()) {
			$user = Sentry::getUser();
			$entries = DB::table('checkins')->get();
			$data = array();

			foreach ($entries as $entry) {
				if ($entry->user_id == $user->id) {
					array_push($data, $entry);
				}
			}
			$data = json_encode($data);
		}

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
				return View::make('checkins.index');
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