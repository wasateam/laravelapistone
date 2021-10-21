<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopClassesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('shop_classes', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->uuid()->unique();
      $table->string('parent_shop_class_id')->nullable();
      $table->string('name')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('shop_classes');
  }
}
