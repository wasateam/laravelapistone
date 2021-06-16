<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdInWebLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('web_logs', function (Blueprint $table) {
      $table->integer('user_id')->nullable()->comment('user who create log');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('web_logs', function (Blueprint $table) {
      $table->dropColumn('user_id');
    });
  }
}
