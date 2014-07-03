<?php

use ML\JsonLD\JsonLD;

 /*
    |--------------------------------------------------------------------------
    | Logincontroller
    |--------------------------------------------------------------------------
 */
class LoginController extends BaseController {

	public function getLogin($provider){
        // dd(Config::get('database'));
        
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();
        // what kind of data the user wants
        switch ($val){
            case "text/html":
                // which provider the user wants to login with
            	switch ($provider) {
            		case "google":
            			$prov = new GoogleProvider;
            		break;
                    case "twitter":
                        $prov = new TwitterProvider;
                    break;  
            	}

                $prov->getLogin();
        
                // response view of travelguide
                return View::make('travelguide.travelGuide');
        	break;

            case "application/ld+json":
            break;

            default:
            break;
        }
    }

    public function postLogin(){

    }
}

?>