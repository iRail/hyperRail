<?php

 /*
    |--------------------------------------------------------------------------
    |   OAuthResourceController
    |   
    |   Access resources of the requested provider
    | 
    |   Route: "irail.dev/{provider}/{id}/friends"
    |--------------------------------------------------------------------------
 */
class OAuthResourceController extends BaseController {

	public function getFriends($provider, $id){
		switch ($provider) {
            		case "google":
            			$prov = new GoogleProvider;
            		break;
                    case "twitter":
                        $prov = new TwitterProvider;
                    break;  
            	}

	}


}
