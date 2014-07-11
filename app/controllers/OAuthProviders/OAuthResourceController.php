<?php

 /*
    |--------------------------------------------------------------------------
    |   OAuthResourceController
    |   
    |   Access resources of the requested provider
    | 
    |   Route: "irail.dev/resource/{provider}/getFriends"
    |--------------------------------------------------------------------------
 */
class OAuthResourceController extends BaseController {

	public function getFriends($provider){
		switch ($provider) {
            case "twitter":
                $prov = new TwitterProvider;
            break;  
    	}

        $prov->getFriends();

	}


}
