<?php

 /*
    |--------------------------------------------------------------------------
    |   OAuthResourceController
    |   
    |   Access resources (ex. friends) of the requested provider
    | 
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

        $prov->getFriends($id);
    }


}
