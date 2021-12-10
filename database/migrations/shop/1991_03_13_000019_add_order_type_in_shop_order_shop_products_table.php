<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderTypeInShopOrderShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_order_shop_products', function (Blueprint $table) {
      $table->text('order_type')->nullable();
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
    Schema::table('shop_order_shop_products', function (Blueprint $table) {
      $table->dropColumn('order_type');
      $table->dropColumn('freight');
    });
  }
}
