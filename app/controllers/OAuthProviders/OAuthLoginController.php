<?php

use ML\JsonLD\JsonLD;

 /*
    |--------------------------------------------------------------------------
    |   OAuthLogincontroller
    |   
    |   Redirects the user to the providerservice he/she wants to log in with
    |   currently only Twitter supported
    | 
    |   Route: "irail.dev/oauth/{provider}"
    |--------------------------------------------------------------------------
 */
class OAuthLoginController extends BaseController {

    public function getLogin($provider){        
        $negotiator = new \Negotiation\FormatNegotiator();
        $acceptHeader = Request::header('accept');
        $priorities = array('text/html', 'application/json', '*/*');
        $result = $negotiator->getBest($acceptHeader, $priorities);
        $val = $result->getValue();
        // what kind of data the user wants
        switch ($val) {
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

                $url = URL::to('route');

                // when the user wants to authorize a third-party app,
                // queryparameters have to be passed
                if(!empty(Input::get('client_id'))){
                    // display an authorization form
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
                    foreach($queryparams as $key => $value){
                        $url .= $key . '=' . $value;
                        $url .= '&';
                    }
                }

                $credentials = $prov->getLogin($url);

                try{
                    $user = Sentry::authenticate($credentials, false);

                    if($user)
                    {
                        $user->save();
                        return Redirect::to($url);
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
}
