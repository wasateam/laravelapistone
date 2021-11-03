<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublishStatusInWsBlogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('ws_blogs', function (Blueprint $table) {
      $table->boolean('publish_status')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('ws_blogs', function (Blueprint $table) {
      $table->dropColumn('publish_status');
    });
  }
}
