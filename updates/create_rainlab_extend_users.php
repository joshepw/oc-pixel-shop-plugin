<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateRainlabExtendUsers extends Migration
{
    public function up()
    {

        if (Schema::hasColumns('users', [
            "billing_address",
            "shipping_address",
            "is_ship_same_bill",
            'phone',
            'pixel_token'
            ])){
            return;
        }

        Schema::table('users', function($table)
        {
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->boolean('is_ship_same_bill')->nullable()->default(false);
            $table->string('phone')->nullable();
            $table->string('pixel_token')->nullable();

        });
    }
    
    public function down()
    {
    	Schema::table('users', function($table){
			$table->dropColumn('billing_address');
			$table->dropColumn('shipping_address');
			$table->dropColumn('is_ship_same_bill');
			$table->dropColumn('phone');
            $table->dropColumn('pixel_token');
		});
    }
    

}