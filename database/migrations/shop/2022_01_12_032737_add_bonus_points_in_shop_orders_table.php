<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonusPointsDeductInShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->integer('bonus_points_deduct')->nullable();
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
      $table->dropColumn('bonus_points_deduct');
    });
  }
}
