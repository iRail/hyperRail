<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplicationNameToOauthClients extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('oauth_clients', function(Blueprint $table) {

        	$table->string('application_name',96)->nullable();

    	});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('oauth_clients', function(Blueprint $table) {

        	$table->dropColumn('application_name');

    	});
	}

}
