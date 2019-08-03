<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRainlabExtendLocation extends Migration
{
	public function up()
	{

		if (!Schema::hasColumns('rainlab_location_states', ["shipping_fee"])){
			Schema::table('rainlab_location_states', function($table){
				$table->decimal('shipping_fee', 10, 2)->nullable()->default(0.00);
			});
		}

		if (!Schema::hasColumns('rainlab_location_countries', ["shipping_fee"])){
			Schema::table('rainlab_location_countries', function($table){
				$table->decimal('shipping_fee', 10, 2)->nullable()->default(0.00);
			});
		}
	}
	
	public function down()
	{
		if (Schema::hasColumns('rainlab_location_states', ["shipping_fee"]))			
		{	
			Schema::table('rainlab_location_states', function($table){
				$table->dropColumn('shipping_fee');
			});
		}
		if (Schema::hasColumns('rainlab_location_countries', ["shipping_fee"]))
		{
			Schema::table('rainlab_location_countries', function($table){
			$table->dropColumn('shipping_fee');
			});
		}
	}
	

}
