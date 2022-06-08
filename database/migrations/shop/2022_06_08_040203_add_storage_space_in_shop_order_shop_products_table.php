<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStorageSpaceInShopOrderShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_order_shop_products', function (Blueprint $table) {
      $table->string('no')->nullable();
      $table->string('storage_space')->nullable();
      $table->string('weight_capacity_unit')->nullable();
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
      $table->dropColumn('no');
      $table->dropColumn('storage_space');
      $table->dropColumn('weight_capacity_unit');
    });
  }
}
