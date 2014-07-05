<?php

class OAuthUsersSeeder extends Seeder
{
	public function run()
	{
		DB::table('oauth_users')->delete();

		DB::table('oauth_users')->insert(array(
			'username' => "bshaffer",
			'password' => sha1("brent123"),
			'first_name' => "Brent",
			'last_name' => "Shaffer",
		));
	}
}