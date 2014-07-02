<?php

use ML\JsonLD\JsonLD;

 /*
    |--------------------------------------------------------------------------
    | Logincontroller
    |--------------------------------------------------------------------------
 */
class LoginController extends BaseController {

	public function redirect($provider){
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
                
                $prov->login();
                // get user id, name from provider
                $data = array('user' => 'p'); 

                // save in database  ...             

                // response view of travelguide
                return Response::view('travelguide.travelGuide', $data)->header('Content-Type', "text/html")->header('Vary', 'accept');;
        	break;

            case "application/ld+json":
            break;

            default:
            break;
        }
    }
}

?>