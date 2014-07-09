<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		//$this->call('TwitterTableSeeder');

		// OAuth-server
		$this->call('OAuthClientsSeeder');
		$this->call('OAuthUsersSeeder');
	}

}