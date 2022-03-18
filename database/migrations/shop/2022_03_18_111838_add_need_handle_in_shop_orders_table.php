<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNeedHandleInShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->boolean('need_handle')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->dropColumn('need_handle');
    });
  }
}
