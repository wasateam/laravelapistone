<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdminIdInCmsLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('cms_logs', function (Blueprint $table) {
      $table->integer('admin_id')->nullable()->comment('user who create log');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('cms_logs', function (Blueprint $table) {
      $table->dropColumn('admin_id');
    });
  }
}
