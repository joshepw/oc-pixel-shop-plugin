<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePixelShopOrders extends Migration
{
    public function up()
    {
        Schema::table('pixel_shop_orders', function($table)
        {
            $table->text('custom_fields')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pixel_shop_orders', function($table)
        {
            $table->dropColumn('custom_fields');
        });
    }
}