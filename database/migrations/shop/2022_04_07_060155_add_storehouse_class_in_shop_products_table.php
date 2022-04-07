<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStorehouseClassInShopProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_products', function (Blueprint $table) {
      $table->string('store_house_class')->nullable();
      $table->string('store_house_subclass')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_products', function (Blueprint $table) {
      $table->dropColumn('store_house_class');
      $table->dropColumn('store_house_subclass');
    });
  }
}
