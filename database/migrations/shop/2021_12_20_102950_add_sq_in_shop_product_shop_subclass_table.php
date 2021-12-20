<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSqInShopProductShopSubclassTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('shop_product_shop_subclass', function (Blueprint $table) {
      $table->string('sq')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('shop_product_shop_subclass', function (Blueprint $table) {
      $table->dropColumn('sq');
    });
  }
}
