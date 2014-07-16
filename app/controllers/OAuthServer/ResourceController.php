<?php

class ResourceController extends BaseController
{
    /**
     * This is called by the client app once the client has obtained an access
     * token for the current user.  If the token is valid, the resource (in this
     * case, the "checkins" of the current user) will be returned to the client
     *
     * @param string $type
     *  A resource-type, ex. checkins
     */
    public function getResource($type)
    {
        // include our OAuth2 Server object
        require_once __DIR__.'/server.php';

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

        switch ($type) {
                case "checkins":
                    $controller = new CheckinController;

                    $url = 'https://irail.dev/'. $type . '/' . $user_id;
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
                break;


                default:
                    return Response::make("No correct parameter given. irail.dev/resource/checkins") ;
                break;  
        }
        
   }
}