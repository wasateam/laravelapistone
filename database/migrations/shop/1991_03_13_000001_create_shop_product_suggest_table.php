<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductSuggestTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_product_suggest', function (Blueprint $table) {
      $table->string('shop_product_id')->nullbale();
      $table->string('suggest_id')->nullbale();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_product_suggest');
  }
}
