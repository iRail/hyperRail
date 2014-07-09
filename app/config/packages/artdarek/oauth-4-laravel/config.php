<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

		/**
		 * Facebook
		 */
        'Facebook' => array(
            'client_id'     => '',
            'client_secret' => '',
            'scope'         => array(),
        ),
        /**
		 * Google
		 */	
		'Google' => array(
	    	    'client_id'     => '20881129840-5q021lj0db53ft5r4rrroq9a6eq2td14.apps.googleusercontent.com
',
	            'client_secret' => '0lTqUREi4IE0wxDwM4xFHhVo',
	            'scope'         => array('userinfo_email', 'userinfo_profile'),
		), 	
		/**
		 * Twitter
		 */	
		'Twitter' => array(
	    	    'client_id'     => 'vSBRhdQeBRjnyXCAZyp7GoLTK',
	            'client_secret' => 'I0UyWipLmq44mF4joAJEZQA7u7fastA09yBmw0tzvTmQdWrZnB',
	            'scope'         => array(),
		), 	

	)

);
