<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStartTimeFormatInAppointmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('appointments', function (Blueprint $table) {
      $table->time('start_time')->nullable()->change();
      $table->time('end_time')->nullable()->change();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('appointments', function (Blueprint $table) {
      $table->string('start_time')->nullable()->change();
      $table->string('end_time')->nullable()->change();
    });
  }
}
