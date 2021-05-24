<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLocaleInAdminsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('admins', function (Blueprint $table) {
      $table->string('locale')->nullable()->default('tw');
      $table->string('sq')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('admins', function (Blueprint $table) {
      $table->dropColumn('locale');
      $table->dropColumn('sq');
    });
  }
}
