<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePixelShopCoupons extends Migration
{
	public function up()
	{
		Schema::create('pixel_shop_coupons', function($table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->unsigned();
			$table->timestamps();
			
			$table->boolean('is_active')->nullable();
			$table->string('code')->nullable();
			
			$table->timestamp('valid_from')->nullable(); 
			$table->timestamp('valid_to')->nullable();
			
			$table->integer('count')->nullable();
			$table->integer('used_count')->nullable()->default(0);
			
			$table->double('minimum_value_cart')->nullable();
			
			$table->double('value')->nullable();
			$table->integer('type_value')->nullable();
		});
	}
	
	public function down()
	{
		Schema::dropIfExists('pixel_shop_coupons');
	}
}