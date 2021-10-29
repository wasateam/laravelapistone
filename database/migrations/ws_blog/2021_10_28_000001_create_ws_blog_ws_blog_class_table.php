<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWsBlogWsBlogClassTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('ws_blog_ws_blog_class', function (Blueprint $table) {
      $table->string('ws_blog_id');
      $table->string('ws_blog_class_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('ws_blog_ws_blog_class');
  }
}
