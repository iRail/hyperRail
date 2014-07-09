<?php

namespace hyperRail\app\models\OAuthProviders;

// Interface for OAuth-providers (Facebook, Twitter etc.)
interface IOAuthProvider{

	public function getLogin();
	
}

?>