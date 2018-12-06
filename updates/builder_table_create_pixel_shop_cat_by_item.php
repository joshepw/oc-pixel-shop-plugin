<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePixelShopCatByItem extends Migration
{
    public function up()
    {
        Schema::create('pixel_shop_cat_by_item', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned()->index();
            $table->integer('item_id')->unsigned()->index();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pixel_shop_cat_by_item');
    }
}
