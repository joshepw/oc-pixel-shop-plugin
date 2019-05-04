<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePixelShopOrders extends Migration
{
    public function up()
    {
        Schema::create('pixel_shop_orders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->boolean('is_paid')->nullable()->default(false);
            $table->boolean('is_confirmed')->nullable()->default(false);
            $table->boolean('is_fulfill')->nullable()->default(false);
            
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();

            $table->integer('user_id')->nullable()->unsigned();
            $table->integer('carrier_id')->nullable()->unsigned();
            $table->integer('coupon_id')->nullable()->unsigned();

            $table->string('gateway', 32);
            $table->string('currency', 5);
            $table->string('status', 32)->default('pending');
            $table->string('customer_first_name', 60);
            $table->string('customer_last_name', 60)->nullable();
            $table->string('customer_email', 60);
            $table->string('customer_phone', 16)->nullable();

            $table->text('response')->nullable();
            $table->text('activity')->nullable();
            $table->text('items')->nullable();
            $table->text('note')->nullable();
            
            $table->decimal('shipping_total', 10, 2)->default(0.00);
            $table->decimal('tax_total', 10, 2)->default(0.00);
            $table->decimal('total', 10, 2)->default(0.00);

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pixel_shop_orders');
    }
}