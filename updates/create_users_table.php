<?php

namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateUsersTable extends Migration
{
	public function up()
	{

		// if (Schema::hasColumns('users', [

		//     'pixel_token2'
		//     ])){
		//     return;
		// }

		Schema::table('users', function ($table) {
			$table->string('pixel_token2')->nullable();
		});
	}

	public function down()
	{
		Schema::table('users', function ($table) {

			$table->string('pixel_token2');
		});
	}
}
