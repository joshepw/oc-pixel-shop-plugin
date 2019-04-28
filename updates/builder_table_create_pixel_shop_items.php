<?php namespace Pixel\Shop\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePixelShopItems extends Migration
{
    public function up()
    {
        Schema::create('pixel_shop_items', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 180);
            $table->string('slug')->nullable();
            $table->string('code', 180)->index()->nullable();
            $table->integer('brand_id')->index()->nullable();
            $table->integer('item_id')->index()->nullable();
            $table->string('type', 60);
            
            $table->integer('quantity')->nullable();
            $table->decimal('price', 10, 2)->nullable()->default(0.00);
            $table->decimal('old_price', 10, 2)->nullable()->default(0.00);
            $table->string('currency', 10);

            $table->integer('sales_count')->nullable()->default(0);
            $table->integer('views_count')->nullable()->default(0);

            $table->boolean('is_visible')->nullable()->default(1);
            $table->boolean('is_with_variants')->nullable()->default(0);
            $table->boolean('is_out_of_stock')->nullable()->default(0);
            $table->boolean('is_on_sale')->nullable()->default(0);

            $table->string('meta_keywords')->nullable();

            $table->double('package_width')->nullable();
            $table->double('package_height')->nullable();
            $table->double('package_depth')->nullable();
            $table->double('package_weight')->nullable();
            $table->decimal('additional_shipping_fees', 10, 2)->nullable()->default(0.00);

            $table->text('variants')->nullable();
            $table->text('description')->nullable();
            $table->string('short_description', 300)->nullable();
            $table->text('tax')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pixel_shop_favorites', function($table)
        {
        	$table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->boolean('is_favorite')->default(false);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pixel_shop_items');
        Schema::dropIfExists('pixel_shop_favorites');
    }
}
