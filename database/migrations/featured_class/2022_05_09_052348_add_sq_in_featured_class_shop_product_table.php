<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSqInFeaturedClassShopProductTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('featured_class_shop_product', function (Blueprint $table) {
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
    Schema::table('featured_class_shop_product', function (Blueprint $table) {
      $table->dropColumn('sq');
    });
  }
}
