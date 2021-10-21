<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_products', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid()->unique();
      $table->string('type')->nullable();
      $table->string('no')->nullable();
      $table->string('name')->nullable();
      $table->string('subtitle')->nullable();
      $table->string('status')->nullable();
      $table->datetime('on_time')->nullable();
      $table->datetime('off_time')->nullable();
      $table->boolean('is_active')->nullable()->default(0);
      $table->string('spec')->nullable();
      $table->integer('cost')->nullable();
      $table->integer('price')->nullable();
      $table->integer('discount_price')->nullable();
      $table->string('weight_capacity')->nullable();
      $table->string('tax')->nullable();
      $table->integer('stock_count')->nullable();
      $table->integer('stock_alert_count')->nullable();
      $table->integer('max_buyable_count')->nullable();
      $table->string('storage_space')->nullable();
      $table->text('cover_image')->nullable();
      $table->longText('description')->nullable();
      $table->string('source')->nullable();
      $table->string('store_temperature')->nullable();
      $table->integer('ranking_score')->nullable();
      $table->string('shop_product_cover_frame_id')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_products');
  }
}
