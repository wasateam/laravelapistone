<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexInShopOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_orders', function (Blueprint $table) {
      $table->index('no');
      $table->index('receiver_tel');
      $table->index('orderer');
      $table->index('orderer_tel');
      $table->index('receiver');
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
      $table->dropIndex('no');
      $table->dropIndex('receiver_tel');
      $table->dropIndex('orderer');
      $table->dropIndex('orderer_tel');
      $table->dropIndex('receiver');
    });
  }
}
