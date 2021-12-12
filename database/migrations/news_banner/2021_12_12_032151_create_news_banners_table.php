<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsBannersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('news_banners', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->softDeletes();
      $table->text('bg_img_1440')->nullable();
      $table->text('bg_img_768')->nullable();
      $table->text('bg_img_320')->nullable();
      $table->text('link')->nullable();
      $table->string('title')->nullable();
      $table->string('title_color')->nullable();
      $table->string('des')->nullable();
      $table->string('des_color')->nullable();
      $table->string('sq')->nullable();
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
    Schema::dropIfExists('news_banners');
  }
}
