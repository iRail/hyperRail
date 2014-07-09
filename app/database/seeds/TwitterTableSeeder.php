<?php

class TwitterTableSeeder extends Seeder {
	public function run() {
		DB::table('users')->delete();

		$users = array(
			array(
				'token'		=>	Hash::make('random_token'),
				'departure'	=>	'https://irail.be/stations/NMBS/008400219/departures/201406060630c6ee1680fdf8b5eb1703dbf41ef84eb0',
				'name' 		=> 	'Brecht',
				'email' 	=> 	'br5cht@hotmail.com'
			)
		);

		DB::table('users')->insert($users);
	}
}