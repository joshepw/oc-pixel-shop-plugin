<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePixelShopBrands extends Migration
{
    public function up()
    {
        Schema::create('pixel_shop_brands', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 180);
            $table->string('slug')->nullable();
            $table->string('image')->nullable();

            $table->boolean('show_in_menu')->nullable();

            $table->string('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pixel_shop_brands');
    }
}
