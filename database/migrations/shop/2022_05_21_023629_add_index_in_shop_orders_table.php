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
      $table->index('no', 'idx_no');
      $table->index('receiver_tel', 'idx_receiver_tel');
      $table->index('orderer', 'idx_orderer');
      $table->index('orderer_tel', 'idx_orderer_tel');
      $table->index('receiver', 'idx_receiver');
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
      $table->dropIndex('idx_no');
      $table->dropIndex('idx_receiver_tel');
      $table->dropIndex('idx_orderer');
      $table->dropIndex('idx_orderer_tel');
      $table->dropIndex('idx_receiver');
    });
  }
}
