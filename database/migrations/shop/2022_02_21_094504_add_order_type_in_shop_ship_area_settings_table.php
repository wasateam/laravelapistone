<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderTypeInShopShipAreaSettingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_ship_area_settings', function (Blueprint $table) {
      $table->string('order_type')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_ship_area_settings', function (Blueprint $table) {
      $table->dropColumn('order_type');
    });
  }
}
