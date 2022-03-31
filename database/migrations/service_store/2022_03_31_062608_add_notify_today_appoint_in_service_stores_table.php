<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotifyTodayAppointInServiceStoresTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->time('today_appointments_notify_time')->nullable();
      $table->time('tomorrow_appointments_notify_time')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('service_stores', function (Blueprint $table) {
      $table->dropColumn('today_appointments_notify_time');
      $table->dropColumn('tomorrow_appointments_notify_time');
    });
  }
}
