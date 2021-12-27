<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartAtInAppointmentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('appointments', function (Blueprint $table) {
      $table->datetime('start_at')->nullable();
      $table->datetime('end_at')->nullable();
      $table->datetime('notify_at')->nullable();
      $table->string('country_code')->nullable();
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
      $table->dropColumn('start_at');
      $table->dropColumn('end_at');
      $table->dropColumn('notify_at');
      $table->dropColumn('country_code');
    });
  }
}
