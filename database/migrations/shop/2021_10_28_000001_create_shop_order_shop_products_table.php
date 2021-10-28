<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopOrderShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_order_shop_products', function (Blueprint $table) {
      // 訂單 id
      $table->string('shop_orders_id')->nullable();
      // 商品 id
      $table->string('shop_products_id')->nullable();
      // 購買數量
      $table->string('count')->nullable();
      // 商品小計
      $table->string('subtotal')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_order_shop_products');
  }
}
