<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrderShopProductSpecsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_order_shop_product_specs', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->integer('shop_order_shop_product_id')->nullable();
      $table->integer('shop_product_spec_id')->nullable();
      $table->integer('cost')->nullable();
      $table->integer('price')->nullable();
      $table->integer('discount_price')->nullable();
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
    Schema::dropIfExists('shop_order_shop_product_specs');
  }
}
