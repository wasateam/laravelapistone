<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCostInShopOrderShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_order_shop_products', function (Blueprint $table) {
      $table->integer('cost')->nullable();
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
      $table->dropColumn('cost');
    });
  }
}
