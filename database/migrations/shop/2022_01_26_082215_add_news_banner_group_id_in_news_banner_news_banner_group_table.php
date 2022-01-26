<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewsBannerGroupIdInNewsBannerNewsBannerGroupTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('news_banner_news_banner_group', function (Blueprint $table) {
      $table->integer('news_banner_group_id');
      $table->string('news_banner_sq')->nullable();
      $table->dropColumn('news_banner_group');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('news_banner_news_banner_group', function (Blueprint $table) {
      $table->dropColumn('news_banner_group_id');
      $table->dropColumn('news_banner_sq');
      $table->integer('news_banner_group');
    });
  }
}
