<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageSettingNewsBannerGroupTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('page_setting_news_banner_group', function (Blueprint $table) {
      $table->integer('page_setting_id');
      $table->integer('news_banner_group_id');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('page_setting_news_banner_group');
  }
}
