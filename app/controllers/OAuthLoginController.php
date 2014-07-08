<?php

use ML\JsonLD\JsonLD;

 /*
    |--------------------------------------------------------------------------
    |   OAuthLogincontroller
    |   
    |   Redirects the user to the providerservice he/she wants to log in with
    | 
    |   Route: "irail.dev/oauth/{provider}"
    |--------------------------------------------------------------------------
 */
class OAuthLoginController extends BaseController {

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

                //Get credentials from provider
                $credentials = $prov->getLogin();
        
                //Authenticate user and redirect to /admin in case of success
                try{

                    $user = Sentry::authenticate($credentials, false);

                    if($user)
                    {
                        return Redirect::to('/admin');
                    }

                }
                catch (\Exception $e)
                {
                    return Redirect::to('/login');
                }


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