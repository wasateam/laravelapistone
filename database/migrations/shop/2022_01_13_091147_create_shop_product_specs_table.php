<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductSpecsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_product_specs', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->integer('shop_product_id')->nullable();
      $table->datetime('on_time')->nullable();
      $table->datetime('off_time')->nullable();
      $table->integer('cost')->nullable();
      $table->integer('price')->nullable();
      $table->integer('discount_price')->nullable();
      $table->integer('stock_count')->nullable();
      $table->integer('stock_alert_count')->nullable();
      $table->integer('max_buyable_count')->nullable();
      $table->string('storage_space')->nullable();
      $table->integer('freight')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_product_specs');
  }
}
