<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsNoLimitedInShopFreeShippingsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_free_shippings', function (Blueprint $table) {
      $table->boolean('is_no_limited')->default(false)->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_free_shippings', function (Blueprint $table) {
      $table->dropColumn('is_no_limited');
    });
  }
}
