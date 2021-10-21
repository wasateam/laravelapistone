<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopProductCoverFramesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_product_cover_frames', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid()->unique();
      $table->string('name')->nullable();
      $table->text('url')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_product_cover_frames');
  }
}
