<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartAtInShopProductSpecsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_product_specs', function (Blueprint $table) {
      $table->datetime('start_at')->nullable();
      $table->datetime('end_at')->nullable();
      $table->dropColumn('on_time');
      $table->dropColumn('off_time');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_product_specs', function (Blueprint $table) {
      $table->datetime('on_time')->nullable();
      $table->datetime('off_time')->nullable();
      $table->dropColumn('start_at');
      $table->dropColumn('end_at');
    });
  }
}
