<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartDateInShopProductCoverFramesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_product_cover_frames', function (Blueprint $table) {
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
      $table->boolean('is_active')->nullable()->default(1);
      $table->text('order_type')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_product_cover_frames', function (Blueprint $table) {
      $table->dropColumn('start_date');
      $table->dropColumn('end_date');
      $table->dropColumn('is_active');
      $table->dropColumn('order_type');
    });
  }
}
