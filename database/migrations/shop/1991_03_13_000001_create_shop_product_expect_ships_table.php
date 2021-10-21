<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductExpectShipsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_product_expect_ships', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid()->unique();
      $table->string('shop_product_id')->nullable();
      $table->date('start_date')->nullable();
      $table->date('end_date')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_product_expect_ships');
  }
}
