<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsBlogsClassTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ws_blogs_class', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      // 編號
      $table->string('no')->nullable();
      // 文章類別名稱
      $table->string('category')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('ws_blogs_class');
  }
}
