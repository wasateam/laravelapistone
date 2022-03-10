<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameTypeInShopShipAreaSettingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_ship_area_settings', function (Blueprint $table) {
      $table->renameColumn('type', 'order_type');
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
      $table->renameColumn('order_type', 'type');
    });
  }
}
